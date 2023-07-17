import pymysql
import pandas as pd

df = pd.read_csv("bps.csv")

df.columns=df.iloc[0]

df = df.loc[1:]


db=pymysql.connect(host='localhost', port=3306, user='username', password = 'password', db = 'database', charset ='utf8')
cursor = db.cursor()
sql = "SELECT cid,merge FROM company WHERE cclass != '存託憑證' AND cclass != 'ETF' AND cclass != 'ETN';"

cursor.execute(sql)
company = cursor.fetchall()
code = [com[0] for com in company]
mer = [com[1] for com in company]


for i in range(len(code)): 
    data = df[df[('公司')]==(mer[i])]
    com = code[i]
    if data.empty == False :
        for year in range(2019,2022):
            bps = data[(str)(year)].values
            if(bps.size > 0):
                bps = bps[0]
                if type(bps) == str: 
                    bps = bps.replace(",","")
                    bps = float(bps)
                print(com,"- - -",year,"= = =",bps)
                
                sql_bps = f"INSERT INTO `bps`(`id`, `year`, `val`) VALUES ('{com}','{year}','{bps}')"
                try:
                    cursor.execute(sql_bps)
                    db.commit()
                except:
                    db.rollback()
                    print("error  bps ---- ",com)


db.close()
