from FinMind.data import DataLoader
import pymysql
import time
#python3 pastPricetwo.py
################# 取得各家公司代號 #################
db=pymysql.connect(host='localhost', port=3306, user='username', password = 'password', db = 'database', charset ='utf8')
cursor = db.cursor()
sql = "SELECT `cid` FROM `company`;"
#sql = "SELECT DISTINCT id FROM `ClosingPrice` WHERE id not in (SELECT id FROM closingprice WHERE `date` = '2023-01-03');"
cursor.execute(sql)
rows = cursor.fetchall()
code = [row[0] for row in rows]

################# 各家公司2023的收盤價 #################

###### for分批(~1006) ######
#2904,2912,3018,4414
start = 901
end = 200
#code[88](4144),4414,3018
for i in range(start,min(start+end,len(code))):
    stock_no = code[i]
    print("now doing: ", code[i], ", ", i)

    ###### 建立Dataframe ######
    try:
        df = DataLoader()
        df.login_by_token(api_token='api_token_here')
        df.login(user_id='userid',password='password')
        temp = df.taiwan_stock_daily(stock_id = stock_no, start_date='2023-01-01', end_date='2023-03-22')
        temp.drop(columns=["Trading_money", "open", "max", "min", "spread", "Trading_turnover"], inplace=True)
        for j in range(len(temp)):
            #print(temp.loc[i][0], temp.loc[i],[1], type(float(temp.loc[i][2])))
            #print(temp.loc[j][1],",", temp.loc[j][0],",", temp.loc[j][2])
            #sql_id = "INSERT INTO ClosingPrice VALUES('" + temp.loc[i][0] + "', '" + temp.loc[i][1] + "', '" + float(temp.loc[i][2]) + "')"
            sql_id = f"INSERT INTO ClosingPrice VALUES('{temp.loc[j][1]}', '{temp.loc[j][0]}', '{temp.loc[j][3]}', '{temp.loc[j][2]}')"
            
            try:
                cursor.execute(sql_id)
                db.commit()
            except :
                db.rollback()
                print("Failed to add id into table ", temp[i])

            
    except Exception as e:
        print("wrong:",e, code[i], ",", i)

db.close()
