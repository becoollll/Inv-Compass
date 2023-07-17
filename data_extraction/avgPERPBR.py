from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from bs4 import BeautifulSoup
import pymysql
import time
import mysql.connector
import requests
import time
import random

################# 取得各家公司代號 #################
r = requests.get("https://tw.stock.yahoo.com/h/kimosel.php?tse=1&cat=%A5b%BE%C9%C5%E9&form=menu&form_id=stock_id&form_name=stock_name&domain=0") #將網頁資料GET下來
soup = BeautifulSoup(r.text,"html.parser") #將網頁資料以html.parser
category_read = soup.select("td.c3 a") #取HTML標中的 <div class="title"></div> 中
#公司種類
category = []
for temp in category_read:
    category.append(temp.text)
    if temp.text == "其他":
        break

code = []
for cat in category:
    #print(cat)
    r = requests.get(f"https://tw.stock.yahoo.com/h/kimosel.php?tse=1&cat={cat}&form=menu&form_id=stock_id&form_name=stock_name&domain=0") 
    soup = BeautifulSoup(r.text,"html.parser") #將網頁資料以html.parser
    company_read = soup.select("a.none") #取HTML標中的 <div class="title"></div> 中
    for tmp in company_read:
        str = tmp.text.strip('\n')
        check = str.split(" ")
        if check[0].isdigit():
            code.append(check[0])
#print(code)

################# chromedriver #################
chrome_options = Options()
chrome_options.add_argument("--headless")
path = "./chromedriver"
browser = webdriver.Chrome(executable_path = path, options=chrome_options)#模擬瀏覽器

################# 爬各家公司per/pbr表格 #################
#python3 PERPBR.py
#3715,6863

db=pymysql.connect(host='localhost', port=3306, user='username', password = 'password', db = 'database', charset ='utf8')
cursor = db.cursor()

for co in range(600, 700):#code:

    print("now do:", code[co], co)
    time.sleep(random.randint(15,30))
    browser.get(f"https://goodinfo.tw/tw/StockBzPerformance.asp?STOCK_ID={code[co]}&RPT_CAT=M_YEAR")#get方式進入網站
    time.sleep(1) #網站有loading時間 
    a = browser.find_element("xpath",'/html/body/table[2]/tbody/tr/td[3]/table[4]/tbody/tr/td/table/tbody/tr/td[1]/nobr[1]/select')
    a.click()
    time.sleep(1)
    b = browser.find_element("xpath", '/html/body/table[2]/tbody/tr/td[3]/table[4]/tbody/tr/td/table/tbody/tr/td[1]/nobr[1]/select/option[3]')
    b.click()
    time.sleep(1)
    soup = BeautifulSoup(browser.page_source, "lxml")
    table = soup.find("table", {"id": "tblDetail"})
    #print(table)
    elements = table.find_all("tr", {"align": "center"})
    data = []
    for i in range(len(elements)):
        data.append([])
        for element in elements[i]:
            data[i].append(element.getText())
    print(data)
    
    for y in range(1, 5):
        if y == 1:
            year = 2022
        elif y == 2:
            year = 2021
        elif y == 3:
            year = 2020
        elif y == 4:
            year = 2019

        if data[y][12] != '-':
            sql_per = f"INSERT INTO AvgPERPBR VALUES('{code[co]}', '{year}', '{0}', '{float(data[y][12])}')"
        else:
            sql_per = f"INSERT INTO AvgPERPBR VALUES('{code[co]}', '{year}', '{0}', NULL)"

        if data[y][16] != '-':
            sql_pbr = f"INSERT INTO AvgPERPBR VALUES('{code[co]}', '{year}', '{1}', '{float(data[y][16])}')"
        else:
            sql_pbr = f"INSERT INTO AvgPERPBR VALUES('{code[co]}', '{year}', '{1}', NULL)"

        try:
            cursor.execute(sql_per)
            cursor.execute(sql_pbr)
            db.commit()
        except mysql.connector.Error as error:
            db.rollback()
            print("Failed to insert record into Laptop table {}".format(error), code[co], co)

db.close()
