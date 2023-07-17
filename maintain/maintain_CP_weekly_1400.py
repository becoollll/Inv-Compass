from FinMind.data import DataLoader
import pymysql
import mysql.connector
import datetime
import logging

#python3 maintain_CP_weekly_1400.py
now_time = datetime.datetime.now()
logging.basicConfig(level=logging.DEBUG,
                    format='%(asctime)s %(levelname)s %(message)s',
                    datefmt='%Y-%m-%d %H:%M',
                    handlers=[logging.FileHandler(f'./maintain/log/{now_time}.log', 'w', 'utf-8'), ])


################# 取得各家公司代號 #################
db=pymysql.connect(host='localhost', port=3306, user='username', password = 'password', db = 'database', charset ='utf8')
cursor = db.cursor()
sql = "SELECT `cid` FROM `company`;"
#sql = "SELECT DISTINCT id FROM `closingprice` WHERE id not in (SELECT id FROM closingprice WHERE `date` = '2023-01-03');"
cursor.execute(sql)
rows = cursor.fetchall()
code = [row[0] for row in rows]
print(code)


################# 各家公司2023的收盤價 #################

yesterday = now_time + datetime.timedelta(days = -1)
end_date = yesterday.strftime('%Y-%m-%d')

fivedays = now_time + datetime.timedelta(days = -5)
start_date = fivedays.strftime('%Y-%m-%d')

###### for分批(~1006) ######
start = 0
end = 600
#code[88](4144),4414,3018,00732
for i in range(start,end):
    stock_no = code[i]
    #print(type(stock_no))
    print("now doing: ", code[i], ", ", i)

    try:
        df = DataLoader()
        df.login_by_token(api_token='api_token_here')
        df.login(user_id='userid',password='password')
        temp = df.taiwan_stock_daily(stock_id = stock_no, start_date=start_date, end_date=end_date)
        temp.drop(columns=[ "Trading_money", "open", "max", "min", "spread", "Trading_turnover"], inplace=True)
        #print(temp)
        for j in range(len(temp)):
            #print(temp.loc[i][0], temp.loc[i],[1], type(float(temp.loc[i][2])))
            #print(temp.loc[j][1],",", temp.loc[j][0],",", temp.loc[j][3],",", temp.loc[j][2])
            
            db=pymysql.connect(host='localhost', port=3306, user='username', password = 'password', db = 'database', charset ='utf8')
            cursor = db.cursor()
            #sql_id = "INSERT INTO ClosingPrice VALUES('" + temp.loc[i][0] + "', '" + temp.loc[i][1] + "', '" + float(temp.loc[i][2]) + "')"
            check_id = f"SELECT * FROM `ClosingPrice` WHERE `id` = '{temp.loc[j][1]}' AND `date`='{temp.loc[j][0]}';"
            cursor.execute(check_id)
            rows = cursor.fetchone()
            if (rows):
            	continue
            print(temp.loc[j][1],",", temp.loc[j][0],",", temp.loc[j][3],",", temp.loc[j][2])	
            sql_id = "INSERT INTO ClosingPrice (`id`, `date`, `price`, `volumn`) VALUES('" + temp.loc[j][1] + "', '" + temp.loc[j][0] + "', {},{})".format(float(temp.loc[j][3]),temp.loc[j][2])
            
            try:
                cursor.execute(sql_id)
                db.commit()
                logging.info(f"{temp.loc[j][1]} update")
            except mysql.connector.Error as error:
                db.rollback()
                print("Failed to add id into table {}".format(error), temp[i])

            db.close()
    except:
        print("wrong:", code[i], ",", i)
