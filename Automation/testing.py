from selenium import webdriver
from selenium.webdriver.common.keys import Keys
import time
browser = webdriver.Chrome('F:/se/testing\chromedriver.exe')
browser.get("http://localhost/se/index.php")
time.sleep(1)
username = browser.find_element_by_name("login")
password = browser.find_element_by_name("password")

username.send_keys("IT-1")
password.send_keys("IT-1")
login_attempt = browser.find_element_by_xpath("//*[@type='submit']")
login_attempt.submit()
