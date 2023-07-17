#檢查所有使用者和公司的條件
#定時啟動
import pymysql
import sys
import datetime
import json
import codecs
sys.stdout = codecs.getwriter('utf-8')(sys.stdout.detach())

db=pymysql.connect(host='localhost', port=3306, user='username', password = 'password', db = 'database', charset ='utf8')
cursor = db.cursor()

date = datetime.date.today() #取得今天日期
try:
    #將執行此檔案的日期存進資料庫(checkdate)
    sqldate = f"UPDATE `reminder` SET `checkdate` = '{date}';"
    cursor.execute(sqldate)
    db.commit()
except Exception as ex:
    db.rollback()
    print(ex)

#+++++++++++++++++++++++++++++++++  PBR  ++++++++++++++++++++++++++++++++++++
def findPBR(user,cpny,symbol,val):
    #取得最新一天的PBR值
    sql1 = f"SELECT * from `pbr` WHERE `id`='{cpny}' ORDER BY `date` DESC;"
    cursor.execute(sql1)
    pbr = cursor.fetchone()
    if(symbol == 0):  #大於
        if(val > pbr[2]):
            print("userid:", user,"id:", cpny, "pbr符合條件", val,">", float(pbr[2]), "<br>")
            try:
                #將符合條件的日期存進資料庫
                sql11 = f"UPDATE `reminder` SET `conformdate` = '{date}', `flag2` = '1' WHERE `teamid`='{user}' and `A`= '1' and`companyid`='{cpny}';"
                cursor.execute(sql11)
                db.commit()
            except Exception as ex:
                print(ex)
        else:
            print("userid:", user,"id:", cpny, "pbr不符合條件", "<br>")

    elif(symbol == 1):  #小於
        if(val < pbr[2]):
            print("userid:", user,"id:", cpny, "pbr符合條件", val,"<", float(pbr[2]), "<br>")
            try:
                #將符合條件的日期存進資料庫
                sql11 = f"UPDATE `reminder` SET `conformdate` = '{date}', `flag2` = '1' WHERE `teamid`='{user}' and  `A`= '1' and `companyid`='{cpny}';"
                cursor.execute(sql11)
                db.commit()
            except Exception as ex:
                print(ex)
        else:
            print("userid:", user,"id:", cpny, "pbr不符合條件", "<br>")
    elif(symbol == 2):  #等於
        if(val == pbr[2]):
            print("userid:", user,"id:", cpny, "pbr符合條件", val,"=", float(pbr[2]), "<br>")
            try:
                #將符合條件的日期存進資料庫
                sql11 = f"UPDATE `reminder` SET `conformdate` = '{date}', `flag2` = '1' WHERE`teamid`='{user}' and `A`= '1' and `companyid`='{cpny}';"
                cursor.execute(sql11)
                db.commit()
            except Exception as ex:
                print(ex)
        else:
            print("userid:", user,"id:", cpny, "pbr不符合條件", "<br>")
    elif(symbol == 3):  #大於等於
        if(val >= pbr[2]):
            print("userid:", user,"id:", cpny, "pbr符合條件", val,">=", float(pbr[2]), "<br>")
            try:
                #將符合條件的日期存進資料庫
                sql11 = f"UPDATE `reminder` SET `conformdate` = '{date}', `flag2` = '1' WHERE `teamid`='{user}' and `A`= '1' and `companyid`='{cpny}';"
                cursor.execute(sql11)
                db.commit()
            except Exception as ex:
                print(ex)
        else:
            print("userid:", user,"id:", cpny, "pbr不符合條件", "<br>")
    elif(symbol == 4):  #小於等於
        if(val <= pbr[2]):
            print("id:", cpny, "pbr符合條件", val,"<=", float(pbr[2]), "<br>")
            try:
                #將符合條件的日期存進資料庫
                sql11 = f"UPDATE `reminder` SET `conformdate` = '{date}', `flag2` = '1' WHERE `teamid`='{user}' and  `A`= '1' and `companyid`='{cpny}';"
                cursor.execute(sql11)
                db.commit()
            except Exception as ex:
                print(ex)
        else:
            print("userid:", user,"id:", cpny, "pbr不符合條件", "<br>")
    
#++++++++++++++++++++++++++++++  PER  ++++++++++++++++++++++++++++++++++
def findPER(user,cpny,symbol,val):
    #取得最新一天的PER值
    sql2 = f"SELECT * from `per` WHERE `id`='{cpny}' ORDER BY `date` DESC;"
    cursor.execute(sql2)
    per = cursor.fetchone()
    #print(per[2])  val在此list的第2(資料庫的row)
    if(symbol == 0):  #大於
        if(val > per[2]):
            print("userid:", user,"id:", cpny, "per符合條件", val,">", float(per[2]), "<br>")
            try:
                #將符合條件的日期存進資料庫
                sql11 = f"UPDATE `reminder` SET `conformdate` = '{date}', `flag2` = '1' WHERE `teamid`='{user}' and  `A`= '2' and `companyid`='{cpny}';"
                cursor.execute(sql11)
                db.commit()
            except Exception as ex:
                print(ex)
        else:
            print("userid:", user,"id:", cpny, "per不符合條件", "<br>")
    elif(symbol == 1):  #小於
        if(val < per[2]):
            print("userid:", user,"id:", cpny, "per符合條件", val,"<", float(per[2]), "<br>")
            try:
                #將符合條件的日期存進資料庫
                sql11 = f"UPDATE `reminder` SET `conformdate` = '{date}', `flag2` = '1' WHERE `teamid`='{user}' and  `A`= '2' and `companyid`='{cpny}';"
                cursor.execute(sql11)
                db.commit()
            except Exception as ex:
                print(ex)
        else:
            print("userid:", user,"id:", cpny, "per不符合條件", "<br>")
    elif(symbol == 2):  #等於
        if(val == per[2]):
            print("userid:", user,"id:", cpny, "per符合條件", val,"=", float(per[2]), "<br>")
            try:
                #將符合條件的日期存進資料庫
                sql11 = f"UPDATE `reminder` SET `conformdate` = '{date}', `flag2` = '1' WHERE `teamid`='{user}' and  `A`= '2' and `companyid`='{cpny}';"
                cursor.execute(sql11)
                db.commit()
            except Exception as ex:
                print(ex)
        else:
            print("userid:", user,"id:", cpny, "per不符合條件", "<br>")
    elif(symbol == 3):  #大於等於
        if(val >= per[2]):
            print("userid:", user,"id:", cpny, "per符合條件", val,">=", float(per[2]), "<br>")
            try:
                #將符合條件的日期存進資料庫
                sql11 = f"UPDATE `reminder` SET `conformdate` = '{date}', `flag2` = '1' WHERE `teamid`='{user}' and `A`= '2' and `companyid`='{cpny}';"
                cursor.execute(sql11)
                db.commit()
            except Exception as ex:
                print(ex)
        else:
            print("userid:", user,"id:", cpny, "per不符合條件", "<br>")
    elif(symbol == 4):  #小於等於
        if(val <= per[2]):
            print("userid:", user,"id:", cpny, "per符合條件", val,"<=", float(per[2]), "<br>")
            try:
                #將符合條件的日期存進資料庫
                sql11 = f"UPDATE `reminder` SET `conformdate` = '{date}', `flag2` = '1' WHERE `teamid`='{user}' and `A`= '2' and `companyid`='{cpny}';"
                cursor.execute(sql11)
                db.commit()
            except Exception as ex:
                print(ex)
        else:
            print("userid:", user,"id:", cpny, "per不符合條件", "<br>")
    
#++++++++++++++++++++++++++  SHARP  ++++++++++++++++++++++++++++++++++
def findSharp(user,cpny,symbol,val):
    #取得最新一天的PER值
    try:
        sql3 = f"SELECT * from `sharp` WHERE `teamid`= '{user}' and `companyid`='{cpny}' ORDER BY `date` DESC;"
        cursor.execute(sql3)
        sharp = cursor.fetchone()  
        #print(sharp[3])  val在此list的第3(資料庫的row)
        if(symbol == 0):  #大於
            if(val > sharp[3]):
                print("userid:", user,"id:", cpny, "sharp符合條件", val,">", float(sharp[3]), "<br>")
                try:
                    #將符合條件的日期存進資料庫
                    sql11 = f"UPDATE `reminder` SET `conformdate` = '{date}', `flag2` = '1' WHERE `teamid`='{user}' and `A`= '3' and `companyid`='{cpny}';"
                    cursor.execute(sql11)
                    db.commit()
                except Exception as ex:
                    print(ex)
            else:
                print("userid:", user,"id:", cpny, "sharp不符合條件", "<br>")
        elif(symbol == 1):  #小於
            if(val < sharp[3]):
                print("userid:", user,"id:", cpny, "sharp符合條件", val,"<", float(sharp[3]), "<br>")
                try:
                    #將符合條件的日期存進資料庫
                    sql11 = f"UPDATE `reminder` SET `conformdate` = '{date}', `flag2` = '1' WHERE `teamid`='{user}' and `A`= '3' and `companyid`='{cpny}';"
                    cursor.execute(sql11)
                    db.commit()
                except Exception as ex:
                    print(ex)
            else:
                print("userid:", user,"id:", cpny, "sharp不符合條件", "<br>")
        elif(symbol == 2):  #等於
            if(val == sharp[3]):
                print("userid:", user,"id:", cpny, "sharp符合條件", val,"=", float(sharp[3]), "<br>")
                try:
                    #將符合條件的日期存進資料庫
                    sql11 = f"UPDATE `reminder` SET `conformdate` = '{date}', `flag2` = '1' WHERE `teamid`='{user}' and `A`= '3' and `companyid`='{cpny}';"
                    cursor.execute(sql11)
                    db.commit()
                except Exception as ex:
                    print(ex)
            else:
                print("userid:", user,"id:", cpny, "sharp不符合條件", "<br>")
        elif(symbol == 3):  #大於等於
            if(val >= sharp[3]):
                print("userid:", user,"id:", cpny, "sharp符合條件", val,">=", float(sharp[3]), "<br>")
                try:
                    #將符合條件的日期存進資料庫
                    sql11 = f"UPDATE `reminder` SET `conformdate` = '{date}', `flag2` = '1' WHERE `teamid`='{user}' and `A`= '3' and `companyid`='{cpny}';"
                    cursor.execute(sql11)
                    db.commit()
                except Exception as ex:
                    print(ex)
            else:
                print("userid:", user,"id:", cpny, "sharp不符合條件", "<br>")
        elif(symbol == 4):  #小於等於
            if(val <= sharp[2]):
                print("userid:", user,"id:", cpny, "sharp符合條件", val,"<=", float(sharp[2]), "<br>")
                try:
                    #將符合條件的日期存進資料庫
                    sql11 = f"UPDATE `reminder` SET `conformdate` = '{date}', `flag2` = '1' WHERE `teamid`='{user}' and `A`= '3' and `companyid`='{cpny}';"
                    cursor.execute(sql11)
                    db.commit()
                except Exception as ex:
                    print(ex)
            else:
                print("userid:", user,"id:", cpny, "sharp不符合條件", "<br>")
    except Exception as ex:
        print(ex)



sql = "select * from `reminder`;" 
cursor.execute(sql) 
data0 = cursor.fetchall() 

sql0 = f"select * from `focus`;" 
cursor.execute(sql0) 
focus = cursor.fetchall() 

#+++++++++++++++++++++++++  更新reminder val的預設值 ++++++++++++++++++++++++++++++++++
#在檢查前確保flag=0的公司value 更新成最新一天的avg
for i in range(len(data0)):
    if(data0[i][5] == 0): #flag = 0 (val是預設值)
        if(data0[i][2] == 1):  #PBR
            sql10 = f"SELECT * from `pbr` WHERE `id`='{data0[i][1]}' ORDER BY `date` DESC;"
            cursor.execute(sql10)
            pbr = cursor.fetchone()
            try:
                pbravg = pbr[3] #從PBR table 取出最新一天的avg
            except Exception as ex:
                pbravg = "-"
                print(ex)
            sql100 = f"UPDATE `reminder` SET `value`= '{pbravg}' WHERE `teamid`='{data0[i][0]}' and  `companyid` = '{data0[i][1]}' and `A` = 1;"
            cursor.execute(sql100)
        elif(data0[i][2] == 2):  #PER
            sql20 = f"SELECT * from `per` WHERE `id`='{data0[i][1]}' ORDER BY `date` DESC;"
            cursor.execute(sql20)
            per = cursor.fetchone()
            try:
                peravg = per[3] #從PER table 取出最新一天的avg
            except Exception as ex:
                peravg = "-"
                print(ex)
            sql200 = f"UPDATE `reminder` SET `value`= '{peravg}' WHERE `teamid`='{data0[i][0]}' and `companyid` = '{data0[i][1]}' and `A` = 2;"
            cursor.execute(sql200)

            #sharp不確定
        elif(data0[i][2] == 3):  #Sharp
            """sql30 = f"SELECT * from `sharp` WHERE `companyid`='{data[i][1]}' ORDER BY `date` DESC;"
            cursor.execute(sql30)
            sharp = cursor.fetchone()
            try:
                sharpavg = sharp[3] #Sharp不確定
            except Exception as ex:
                sharpavg = "-"
                print(ex)"""
            sql300 = f"UPDATE `reminder` SET `value`= 0 WHERE `teamid`='{data0[i][0]}' and `companyid` = '{data0[i][1]}' and `A` = 3;"
            cursor.execute(sql300)
    else:
        continue
        
sql0 = f"select * from `focus` WHERE `f` = 1;" 
cursor.execute(sql0) 
focus = cursor.fetchall() 

"""sql = "select * from `reminder` ;" 
cursor.execute(sql) 
data = cursor.fetchall()  #fetchall回傳的是list"""

#+++++++++++++++++++++++++++++++++  main   +++++++++++++++++++++++++++++++
date = datetime.date.today() #取得今天日期
count = 0
#在今天檢查前把flag2 重設為0
sql00 = f"UPDATE `reminder` SET `flag2` = '0';"
cursor.execute(sql00)
db.commit()

for i in range(len(focus)):
    temp = 0
    if(focus[i][2] == 1): #有關注該公司 f==1
    	#print(focus[i][0],focus[i][1])
    	sql = f"select * from `reminder` WHERE `companyid` = '{focus[i][1]}' AND `teamid`= '{focus[i][0]}';" 
    	cursor.execute(sql) 
    	data = cursor.fetchall()
    	if(data):
    		for k in range(len(data)):  #有幾個指標就跑幾次回圈 取得結果
    			#print(focus[i][0],focus[i][1])
    			#print(data[k][0],data[k][1],data[k][2], data[k][3], data[k][4])
    			count = count + 1
    			if(data[k][2] == 1 ):  #PBR
    				findPBR(focus[i][0],focus[i][1], data[k][3], data[k][4])
    			elif(data[k][2] == 2):  #PER
    				findPER(focus[i][0],focus[i][1], data[k][3], data[k][4])
    			elif(data[k][2] == 3):  #Sharp
    				findSharp(focus[i][0],focus[i][1], data[k][3], data[k][4])
    else:
        continue
print("focus",len(focus))
print("reminder",count)



db.close()
