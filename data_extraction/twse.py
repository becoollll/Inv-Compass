import requests
from bs4 import BeautifulSoup
import pymysql
import time
import random 
#python3 year.py
def change (s):
    if(type(s)==str):
        s = s.replace("(","-").strip(')').replace(",","")
    return int(s)

db=pymysql.connect(host='localhost', port=3306, user='username', password = 'password', db = 'database', charset ='utf8')

seconds = time.time()
end = year = time.localtime(seconds).tm_year-1

print("\n從現在到過去n年 \nex: 現在:2022 輸入:4  -> 2022~2019 \n")
print(f"Last year : {year}")
while(1):
    #temp = int(input("下載的年份個數 (1~4): "))
    temp = 1
    start = end - temp + 1
    if temp > 0 and start > year-10: 
        print(start,"~",end)
        break
#ccc = [ '3018', '4414']    
with db.cursor() as cursor:
    sql_id = "SELECT cid, cclass FROM company WHERE cclass != '存託憑證' AND cclass != 'ETF' AND cclass != 'ETN';"
    #i = 2
    #sql_id = f"SELECT cid, cclass FROM company WHERE cid = '{ccc[i]}' OR cid = '{ccc[i+1]}';"
    cursor.execute(sql_id)
    rows = cursor.fetchall()
    print(rows)
    company = [row[0] for row in rows]
    comclass = [row[1] for row in rows]
    #['3018', '4414']


    """while(1):
        com_len = int(input(f"公司數量(公司個數:{len(company)}) : ")) 
        com_start = int(input(f"起始公司(公司個數:0~{len(company)-com_len}) : "))  #起始 1
        if(com_len > 0 and com_start >= 0 and com_start+com_len <= len(company)):
            break"""
    com_len = len(company)
    com_start = 0
        
    nullcom = []
    success = []
    count = 0
    delay_choices = [i for i in range(5,10)]
    index = ['roa','roe']
    
    for i in range(com_start,min(len(company),com_start+com_len)):
        if(company[i]):
            com = company[i]
            clas = comclass[i]
            print("-----------")
            print(com,"---",clas)
            
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
            
            for y in range (start,end+1):
                delay = random.choice(delay_choices)
                print(i,"----",delay)
                time.sleep(delay)
                print(y)
                try :
                    url = f"https://mops.twse.com.tw/server-java/t164sb01?step=3&SYEAR={y}&file_name=tifrs-fr1-m1-ci-cr-{com}-{y}Q4.html"
                    stock = requests.get(url)
                    #print("get1")
                    stock.encoding='utf-8'
                    s = BeautifulSoup(stock.text, "html.parser")  # 轉換成標籤樹
                    count = 0
                    while( s.find("h4") != None ):
                        count = count + 1
                        if(clas != "金融業"):
                            if count == 1:
                                url = f"https://mops.twse.com.tw/server-java/t164sb01?step=3&SYEAR={y}&file_name=tifrs-fr1-m1-ci-ir-{com}-{y}Q4.html"
                            elif count == 2:
                                url = f"https://mops.twse.com.tw/server-java/t164sb01?step=3&SYEAR={y}&file_name=tifrs-fr1-m2-ci-cr-{com}-{y}Q4.html"
                            elif count == 3:
                                url = f"https://mops.twse.com.tw/server-java/t164sb01?step=3&SYEAR={y}&file_name=tifrs-fr1-m1-mim-cr-{com}-{y}Q4.html"
                            elif count == 4:
                                url = f"https://mops.twse.com.tw/server-java/t164sb01?step=3&SYEAR={y}&file_name=tifrs-fr2-m1-ci-cr-{com}-{y}Q4.html"
                            else:
                                print("None!!")
                                break
                        else:
                            time.sleep(5)
                            if count == 1:
                                url = f"https://mops.twse.com.tw/server-java/t164sb01?step=3&SYEAR={y}&file_name=tifrs-fr1-m1-fh-cr-{com}-{y}Q4.html"
                            elif count == 2:
                                url = f"https://mops.twse.com.tw/server-java/t164sb01?step=3&SYEAR={y}&file_name=tifrs-fr1-m1-basi-cr-{com}-{y}Q4.html"
                            elif count == 3:
                                url = f"https://mops.twse.com.tw/server-java/t164sb01?step=3&SYEAR={y}&file_name=tifrs-fr1-m1-ins-ir-{com}-{y}Q4.html"
                            elif count == 4:
                                url = f"https://mops.twse.com.tw/server-java/t164sb01?step=3&SYEAR={y}&file_name=tifrs-fr1-m1-basi-ir-{com}-{y}Q4.html"
                            elif count == 5:
                                url = f"https://mops.twse.com.tw/server-java/t164sb01?step=3&SYEAR={y}&file_name=tifrs-fr1-m1-bd-cr-{com}-{y}Q4.html"
                            elif count == 6:
                                url = f"https://mops.twse.com.tw/server-java/t164sb01?step=3&SYEAR={y}&file_name=tifrs-fr2-m1-fh-cr-{com}-{y}Q4.html"
                            elif count == 7:
                                url = f"https://mops.twse.com.tw/server-java/t164sb01?step=3&SYEAR={y}&file_name=tifrs-fr2-m1-ins-ir-{com}-{y}Q4.html"
                            elif count == 8:
                                url = f"https://mops.twse.com.tw/server-java/t164sb01?step=3&SYEAR={y}&file_name=tifrs-fr1-m1-ins-cr-{com}-{y}Q4.html"
                            else:
                                print("金融業 None!!")
                                break
                        stock = requests.get(url)
                        s = BeautifulSoup(stock.text, "html.parser")
                    BalanceSheet = s.find(id="BalanceSheet").find_next_sibling().find_next_sibling()
                    IncomeStatement = s.find(id="StatementOfComprehensiveIncome").find_next_sibling().find_next_sibling()
                    print("count:",count)
                except:
                    print("ERROR !!!!")
                    nullcom.append(com)
                    continue
               
                    
                for b in BalanceSheet.find_all("td"):
                    if b.getText() == "1XXX":
                        cur_asset = change(b.find_all_next()[3].getText())
                        last_asset = change(b.find_all_next()[6].getText())
                        print(cur_asset,last_asset)
                    elif b.getText() == "3XXX":
                        cur_equity = change(b.find_all_next()[3].getText())
                        last_equity = change(b.find_all_next()[6].getText())
                        print(cur_equity,last_equity)
                        break
                    elif(clas == "金融業"):
                        if b.getText() == "1XXXX" or b.getText() == "10000" or b.getText() == "906001"or b.getText() == "19999":
                            cur_asset = change(b.find_all_next()[3].getText())
                            last_asset = change(b.find_all_next()[6].getText())
                            print(clas,"---",cur_asset,last_asset)
                        elif b.getText() == "3XXXX"or b.getText() == "30000" or b.getText() == "906004"or b.getText() == "39999":
                            cur_equity = change(b.find_all_next()[3].getText())
                            last_equity = change(b.find_all_next()[6].getText())
                            print(clas,"---",cur_equity,last_equity)
                            break
                    
                for k in IncomeStatement.find_all("span",class_="en"):
                    if k.getText() == "Profit (loss)" :
                        cur_income = change(k.find_all_next()[1].getText())
                        print(cur_income)
                        break
                
                roe = round(cur_income*100/((cur_equity+last_equity)/2),2)
                roa = round(cur_income*100/((cur_asset+last_asset)/2),2)
                print(roa ,"  -  ",roe)
                
                sql_roa = f"INSERT INTO `roa`(`id`, `year`, `val`) VALUES ('{com}','{y}','{roa}')"
                sql_roe = f"INSERT INTO `roe`(`id`, `year`, `val`) VALUES ('{com}','{y}','{roe}')"
                try:
                    cursor.execute(sql_roa)
                    db.commit()
                    success.append(com)
                except:
                    db.rollback()
                    print("error  roa ---- ",com)
                try:
                    cursor.execute(sql_roe)
                    db.commit()
                except:
                    db.rollback()
                    print("error  roe ---- ",com)
db.close()
print("success:",success)
print("nullcom:",nullcom)
