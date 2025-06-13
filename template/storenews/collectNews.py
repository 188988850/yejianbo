import time
import json
import pymysql
from selenium import webdriver
from selenium.webdriver.chrome.options import Options

# 数据库配置
db = pymysql.connect(
    host="localhost",
    port=3306,
    user="xinfaka",
    password="82514a97de548852",
    database="xinfaka",
    charset="utf8mb4"
)
cursor = db.cursor()

# Selenium配置
chrome_options = Options()
chrome_options.add_argument('--headless')
chrome_options.add_argument('--disable-gpu')
chrome_options.add_argument('--no-sandbox')
chrome_options.add_argument('--window-size=1920,1080')
chrome_options.add_argument('--disable-dev-shm-usage')
chrome_options.add_argument('--disable-blink-features=AutomationControlled')
chrome_options.add_argument('--user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36')

driver = webdriver.Chrome(executable_path="./chromedriver", options=chrome_options)

# 采集前3页
for page in range(1, 4):
    api_url = f"https://web.aimallol.com/notice?type=10&page={page}&status=0"
    driver.get(api_url)
    time.sleep(2)
    body = driver.find_element("tag name", "pre").text if driver.find_elements("tag name", "pre") else driver.find_element("tag name", "body").text
    try:
        data = json.loads(body)
    except Exception as e:
        print(f"第{page}页解析失败: {e}")
        continue
    if not data or data.get("code") != 200:
        print(f"第{page}页采集失败，code={data.get('code') if data else '无'}")
        continue
    for item in data['data']['data']:
        id = item['id']
        title = item['title']
        img = item['img'].replace('\\/', '/')
        views = item['hit']
        t = time.strftime('%Y-%m-%d %H:%M:%S', time.localtime(item['create_time']))
        desc = item.get('desc', '')
        url = f"https://web.aimallol.com/info/{id}?uid=60405"
        content = ''
        sql = "INSERT IGNORE INTO shua_news (title, url, `desc`, time, views, content, img) VALUES (%s, %s, %s, %s, %s, %s, %s)"
        cursor.execute(sql, (title, url, desc, t, views, content, img))
        db.commit()
    print(f"第{page}页采集完成！")

driver.quit()
db.close()
print("全部采集完成！")