import requests
from bs4 import BeautifulSoup
import pymysql
import pandas as pd
import time
import numpy as np
import random
 
#python3 year.py

seconds = time.time()
end = year = time.localtime(seconds).tm_year-1

print("從現在到過去n年 \nex: 現在:2022 輸入:4  -> 2022~2019 ")
while(1):
    temp = int(input("下載的年份個數 (1~4): "))
    start = end - temp + 1
    if temp > 0 and start > year-5: 
        print(start,"~",end)
        break
    
db=pymysql.connect(host='localhost', port=3306, user='username', password = 'password', db = 'database', charset ='utf8')
with db.cursor() as cursor:
    sql_user = "SELECT cid FROM company WHERE cclass != '存託憑證' AND cclass != 'ETF' AND cclass !='ETN'"
    cursor.execute(sql_user)
    company = cursor.fetchall()
    #print(company)
    index = ['bps','eps']
    nullcom = []
    success = []
    for i in range(len(company)):
        if(company[i]):
            com = company[i][0]
            print("-----------")
            print(com)
            check_count = 0
            for j in range(len(index)):
                sql_com = f"SELECT year FROM {index[j]} WHERE year <= {end} and year >= {start} and id = {com};"
                cursor.execute(sql_com)
                check = cursor.fetchall()
                print(index[j] ," = = = = ",len(check))
                if len(check) == temp :
                    check_count = check_count + 1
                else:
                    break
                
            if check_count == len(index):
                continue
            nullcom.append(com) 
            continue
            stock = requests.get(f"https://tw.stock.yahoo.com/quote/{com}/profile")
            stock .encoding='utf-8'
            s = BeautifulSoup(stock.text, "html.parser")  # 轉換成標籤樹
            data_index = s.find_all("div", class_= "D(f) Ai(c) H(40px) Fz(16px) Bxz(bb) BdB Bdc($bd-primary-divider) Pstart(12px) Bgc($c-gray-hair) C($c-primary-text) Fw(b) Bdtw(1px) Bdts(s)")[0]
            
            check = data_index.getText()
            
            if check == f'{year} Q4 獲利能力' and year == end:
                print('yes')
                profitability = data_index.find_next_sibling()
                
                for b in profitability.find_all("span",class_="As(st) Bxz(bb) Pstart(12px) Py(8px) Bgc($c-gray-hair) C($c-primary-text) Flx(n) W(104px) W(120px)--mobile W(152px)--wide Miw(u) Pend(12px) Mend(0)"):
                    if b.getText() == "每股淨值":
                        bps_p = b
                        bps = float(b.find_next_sibling().getText().strip(' 元'))
                        sql_bps = f"INSERT INTO `bps`(`id`, `year`, `val`) VALUES ('{com}','{year}','{bps}')"
                        try:
                            cursor.execute(sql_bps)
                            db.commit()
                        except:
                            db.rollback()
                            print("error  bps ---- ",com)
                        break
                
                EPS_year = profitability.find_next_sibling()
                t = temp-1
                for e in EPS_year.find_all("span",class_="As(st) Bxz(bb) Pstart(12px) Py(8px) Bgc($c-gray-hair) C($c-primary-text) Flx(n) W(104px) W(120px)--mobile W(152px)--wide Miw(u) Pend(12px) Mend(0)"):
                    if e.getText() == f"{start+t}" and t >= 0:
                        eps = float(e.find_next_sibling().getText().strip(' 元'))
                        print(start+t)
                        sql_eps = f"INSERT INTO `eps`(`id`, `year`, `val`) VALUES ('{com}','{start+t}','{eps}')"
                        try:
                            cursor.execute(sql_eps)
                            db.commit()
                            success.append(com)
                        except:
                            db.rollback()
                            print("error  eps ---- ",com)
                        t = t - 1
            else:
                print("no")
                t = temp-2
                EPS_year = data_index.find_next_sibling().find_next_sibling()
                for e in EPS_year.find_all("span",class_="As(st) Bxz(bb) Pstart(12px) Py(8px) Bgc($c-gray-hair) C($c-primary-text) Flx(n) W(104px) W(120px)--mobile W(152px)--wide Miw(u) Pend(12px) Mend(0)"):
                    if e.getText() == f"{start+t}" and t >= 0:
                        eps = float(e.find_next_sibling().getText().strip(' 元'))
                        print(start+t)
                        sql_eps = f"INSERT INTO `eps`(`id`, `year`, `val`) VALUES ('{com}','{start+t}','{eps}')"
                        try:
                            cursor.execute(sql_eps)
                            db.commit()
                        except:
                            db.rollback()
                            print("error  eps ---- ",com)
                        t = t - 1
db.close()

print("success:",success)
print("nullcom:",nullcom)

