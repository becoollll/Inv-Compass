import requests
from bs4 import BeautifulSoup
import pymysql
import pandas as pd
import time
import numpy as np
import random 

seconds = time.time()
end = year = time.localtime(seconds).tm_year-1

print("\n從現在到過去n年 \nex: 現在:2022 輸入:4  -> 2022~2019 \n")
print(f"last year : {year}")

while(1):
    #temp = int(input("下載的年份個數 (1~4): "))
    temp = 5
    start = end - temp + 1
    if temp > 0 and start > year-10: 
        print(start,"~",end)
        break
    
db=pymysql.connect(host='localhost', port=3306, user='username', password = 'password', db = 'database', charset ='utf8')
with db.cursor() as cursor:
    sql_id = "SELECT cid FROM company WHERE cclass != '存託憑證' AND cclass != 'ETF' AND cclass != 'ETN';"
    cursor.execute(sql_id)
    rows = cursor.fetchall()
    company = [row[0] for row in rows]
    company = ['3018', '3714', '3715', '4414', '6807', '6854']
    #['3018', '3714', '3715', '4414', '6807', '6854']

    print(len(company))
    
    while(1):
        #com_len = int(input(f"公司數量(公司個數:{len(company)}) : ")) 
        com_len = len(company)
        com_start = 0
        #com_start = int(input(f"起始公司(公司個數:0~{len(company)-com_len}) : "))  #起始 201
        if(com_len > 0 and com_start >= 0 and com_start+com_len <= len(company)):
            break

    nullcom = []
   
#python3 year.py
    count = 0
    delay_choices = [i for i in range(15,31)]
    index = ['roa','roe','bps','eps']
    
    for i in range(com_start,min(len(company),com_start+com_len)):
        if(company[i]):
            com = company[i]
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
                count = count + 1
                continue
            #nullcom.append(com)
            delay = random.choice(delay_choices)
            print(i,"----",delay)
            time.sleep(delay)
            try :
                url = f"https://goodinfo.tw/StockInfo/StockBzPerformance.asp?STOCK_ID={com}"
                headers = {
                    "user-agent":"Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/111.0.0.0 Safari/537.36"
                }
                res = requests.get(url,headers=headers)
                res.encoding = "utf-8"
            
                soup = BeautifulSoup(res.text,"lxml")
                data = soup.select_one("#txtFinDetailData")
                
                dfs = pd.read_html(data.prettify())
                df = dfs[0][:6]
                print(df)
                count = count + 1
            except:
                print("ERROR !!!!")
                nullcom.append(com)
                continue
            print("\n",i,"- - - -",com)
            if(len(df)>2):
                for y in range(start,end+1):
                    print(y)
                    #print(type(y))
                    if type(df[('年度','年度')].values[0]) ==  np.int64 :
                        data = df[df[('年度','年度')]==y]
                    else:
                        data = df[df[('年度','年度')]==str(y)]
                    #print(data)
                    if data.empty == False :
                        #INDEX   ROE:16 ,ROA:17 ,EPS:18,BPS:20
                        roa = data[('ROA  (%)','ROA  (%)')].values
                        roe = data[('ROE  (%)','ROE  (%)')].values
                        bps = data[('BPS  (元)','BPS  (元)')].values
                        eps = data[('EPS(元)', '稅後  EPS')].values
                        print(roa[0],roe[0],bps[0],eps[0])
                        if (roa[0] != '-'):
                            sql_roa = f"INSERT INTO `roa`(`id`, `year`, `val`) VALUES ('{com}','{y}','{roa[0]}')"
                            try:
                                cursor.execute(sql_roa)
                                db.commit()
                            except:
                                db.rollback()
                                print("error  roa ---- ",com)
                        if (roe[0] != '-'):
                            sql_roe = f"INSERT INTO `roe`(`id`, `year`, `val`) VALUES ('{com}','{y}','{roe[0]}')"
                            try:
                                cursor.execute(sql_roe)
                                db.commit()
                            except:
                                db.rollback()
                                print("error  roe ---- ",com)
                        if (bps[0] != '-'):
                            sql_bps = f"INSERT INTO `bps`(`id`, `year`, `val`) VALUES ('{com}','{y}','{bps[0]}')"
                            try:
                                cursor.execute(sql_bps)
                                db.commit()
                            except:
                                db.rollback()
                                print("error  bps ---- ",com)
                        if (eps[0] != '-'):
                            sql_eps = f"INSERT INTO `eps`(`id`, `year`, `val`) VALUES ('{com}','{y}','{eps[0]}')"
                            try:
                                cursor.execute(sql_eps)
                                db.commit()
                            except:
                                db.rollback()
                                print("error  eps ---- ",com)
                    else:
                        print("Empty!!")
                        nullcom.append(com)
                        
        else:
            break
db.close()
print(len(nullcom))
print(nullcom)
