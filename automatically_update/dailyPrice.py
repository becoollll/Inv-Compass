from bs4 import BeautifulSoup
import requests
import pymysql
import time
#import pandas as pd
#python3 dailyPrice.py
db=pymysql.connect(host='localhost', port=3306, user='username', password = 'password', db = 'database', charset ='utf8')

seconds = time.time()
year = time.localtime(seconds).tm_year-1

cursor = db.cursor()
sql_user = "SELECT cid FROM company "
cursor.execute(sql_user)
rows = cursor.fetchall()
company = [row[0] for row in rows]
print(company)
def price(code):
    comlist = []
    for i in range(len(code)):
        com = code[i]
        print(com,"----",i)
        try:
            stock = requests.get(f"https://tw.stock.yahoo.com/quote/{com}/profile")
            s = BeautifulSoup(stock.text, "html.parser")  # 轉換成標籤樹
            
            t = s.find_all("span", class_= "C(#6e7780) Fz(12px) Fw(b)")[0]
            tim = t.getText()[5:15].replace("/","-")
            print(tim)
            
            p = s.find("div", class_= "D(f) Ai(fe) Mb(4px)").find_next()
            price = p.getText().replace(",","")
            print(price)
            
            v = s.find("div", class_= "D(f) Fld(c) Ai(c) Fw(b) Pend(8px) Bdendc($bd-primary-divider) Bdends(s) Bdendw(1px)").find_next()
            volume = v.getText().replace(",","")
            print(volume)

            if price != "-":
                sqlp = f"INSERT INTO `ClosingPrice` VALUES ('{com}','{tim}','{price}','{volume}')"
                try:
                    cursor.execute(sqlp)
                    db.commit()
                except:
                    db.rollback()
                    print("error price",com)
                    #comlist.append(com)
        except:
            comlist.append(com)

        
    print("error :",comlist)
    return comlist

coml = price(company)
l = price(coml)

sql_date = "SELECT DISTINCT date FROM `ClosingPrice` ORDER by date DESC;"
cursor.execute(sql_date)
d = cursor.fetchone()
if (d) :
    date = d[0]
    print(date,year)
    sql_per = f"INSERT INTO per (id,date,val) SELECT p.id,p.date,round((p.price/e.val),2) FROM ClosingPrice p,eps e where e.year = '{year}' and p.date = '{date}' and  p.id = e.id and e.val != 0;"
    sql_pbr = f"INSERT INTO pbr (id,date,val) SELECT p.id,p.date,round((p.price/b.val),2) FROM ClosingPrice p,bps b where b.year = '{year}' and p.date = '{date}' and  p.id = b.id and b.val != 0;"
    sql_per_z = f"INSERT INTO per (id,date,val) SELECT p.id,p.date,e.val FROM ClosingPrice p,eps e where e.year = '{year}' and p.date = '{date}' and  p.id = e.id and e.val = 0;"
    sql_pbr_z = f"INSERT INTO pbr (id,date,val) SELECT p.id,p.date,b.val FROM ClosingPrice p,bps b where b.year = '{year}' and p.date = '{date}' and  p.id = b.id and b.val = 0;"
    sql_per_avg = f"UPDATE per bp JOIN (SELECT a1.id ,a1.date, ROUND(AVG(a1.val) over (PARTITION BY a2.cclass),2) as avg FROM per a1 ,company a2 WHERE a1.id = a2.cid and a1.date = '{date}' ) b ON bp.id = b.id and bp.date = b.date SET bp.avg = b.avg ;"
    sql_pbr_avg = f"UPDATE pbr bp JOIN (SELECT a1.id,a1.date, ROUND(AVG(a1.val) over (PARTITION BY a2.cclass),2) as avg FROM pbr a1 ,company a2 WHERE a1.id = a2.cid and a1.date = '{date}' ) b ON bp.id = b.id and bp.date = b.date SET bp.avg = b.avg ;"
    try:
        cursor.execute(sql_per)
        db.commit()
        print("per ok")
        cursor.execute(sql_per_z)
        db.commit()
        print("per z ok")
        cursor.execute(sql_per_avg)
        db.commit()
        print("per avg ok")
    except:
        db.rollback()
        print("error  per ----  ",date)
    try:
        cursor.execute(sql_pbr)
        db.commit()
        print("pbr ok")
        cursor.execute(sql_pbr_z)
        db.commit()
        print("pbr z ok")
        cursor.execute(sql_pbr_avg)
        db.commit()
        print("pbr avg ok")
    except:
        db.rollback()
        print("error  pbr ----  ",date)
    
db.close()
