import pymysql
import pandas as pd
import datetime
import numpy as np
#from empyrical import sharpe_ratio
db=pymysql.connect(host='localhost', port=3306, user='username', password = 'password', db = 'database', charset ='utf8')
def std_dev(data):
    # Get number of observations
    n = len(data)
    # Calculate mean
    mean = sum(data) / n
    # Calculate deviations from the mean
    deviations = sum([(x - mean)**2 for x in data])
    # Calculate Variance & Standard Deviation
    variance = deviations / (n - 1)
    s = variance**(1/2)
    return s

today = datetime.date.today()
with db.cursor() as cursor:
    sql_user = " SELECT DISTINCT `teamid`,`companyid` FROM `StockTrading` ORDER BY `teamid`"
    cursor.execute(sql_user)
    rows = cursor.fetchall()
    print(rows)
    
    teamid = np.unique([x[0]  for x in rows])
    company = [[x[1]  for x in rows if x[0] == i] for i in teamid ]
    risk_free_rate=0.01575
    for i in range(len(teamid)):
        print(teamid[i])
        for com in company[i]: #公司
            print(com)
            sql_trading = f"""SELECT b.`teamid`,b.`companyid`,b.`date`,SUM(b.`totalshare`) totalshare,SUM(b.`price`*b.`totalshare`) totalcost 
            FROM (SELECT `teamid`,`companyid`,`date`,`price`,CASE (`buyORsell`) WHEN (0) THEN `shareANDlot`*(1+`unit`*999) 
            WHEN (1) THEN `shareANDlot`*-(1+`unit`*999) END totalshare FROM `StockTrading` WHERE `teamid` = {teamid[i]} and `companyid`='{com}' )b 
            GROUP BY b.`date`,b.`teamid`,b.`companyid` ORDER BY `b`.`date` ASC;"""
            cursor.execute(sql_trading)
            row = cursor.fetchall()
            cost = [[x[2],(int)(x[3]),x[4]] for x in row] #  [[datetime.date(2023, 4, 7), 1000, 100000.0]]
            #print(cost)
            
            t = 0  #cost[0][0] 起始交易時間
            sqlp = f"SELECT * FROM `ClosingPrice` WHERE `date` BETWEEN '{cost[t][0]}' AND '{today}' AND `id` = '{com}';"
            cursor.execute(sqlp)
            row = cursor.fetchall()
            price = [[x[1],x[2]] for x in row]
            #print(price)#市價
            
            totalcost = 0
            totalshare = 0
            newprice = []
            return_ratio = []
            return_day = [x[0] for x in price]
            for j in range(len(price)):
                try:
                    d = price[j][0]
                    newprice.append(price[j][1])
                    if (d == cost[t][0]):
                        totalcost = totalcost + cost[t][2] #成本
                        totalshare = totalshare + cost[t][1] #股數
                        
                        unitcost = totalcost/totalshare
                        t = min(t + 1,len(cost)-1)
                    return_ratio.append(round(sum(newprice)/(unitcost*(len(newprice)))-1,4))
                    #print(np.mean(return_ratio),"-----")
                    if(j != 0):
                        sharpe_ra = ((np.mean(return_ratio) - risk_free_rate) / std_dev(return_ratio)) * (252 ** 0.5)
                        #print(np.std(return_ratio),",",std_dev(return_ratio))
                        #sr = sharpe_ratio(np.array(return_ratio), risk_free=risk_free_rate, period='daily', annualization=None)
                        #print(sharpe_ra ,",",sr)
                        sqlp = f"SELECT * FROM `sharp` WHERE `teamid` = {teamid[i]} and `companyid`='{com}' AND `date` = '{d}';"
                        cursor.execute(sqlp)
                        row = cursor.fetchall()
                        if (row):
                            print("update")
                            print(sharpe_ra,"---",d)
                            sqlp = f"UPDATE `sharp` SET `val`='{sharpe_ra}' WHERE `teamid` = {teamid[i]} and `companyid`='{com}' AND `date` = '{d}';"
                            try:
                                cursor.execute(sqlp)
                                db.commit()
                            except:
                                db.rollback()
                                print("error update sharpe",com)
                        else:
                            print("insert")
                            print(sharpe_ra,"---",d)
                            sqlp = f"INSERT INTO `sharp` VALUES ('{teamid[i]}','{com}','{d}','{sharpe_ra}')"
                            try:
                                cursor.execute(sqlp)
                                db.commit()
                            except:
                                db.rollback()
                                print("error insert sharpe",com)
                except:
                    print("---error ",d,com)
            print(return_ratio)
            print("===//////")
            
            
        print("===")
        
db.close()


"""
SELECT b.`teamid`,b.`companyid`,SUM(b.`totalshare`) totalshare,SUM(b.`price`*b.`totalshare`) totalcost ,SUM(b.`price`*b.`totalshare`)/SUM(b.`totalshare`) 
            FROM (SELECT `teamid`,`companyid`,`date`,`price`,CASE (`buyORsell`) WHEN (0) THEN `shareANDlot`*(1+`unit`*999) 
            WHEN (1) THEN `shareANDlot`*-(1+`unit`*999) END totalshare FROM `StockTrading` )b 
            GROUP BY b.`teamid`,b.`companyid` ;
            

"""
