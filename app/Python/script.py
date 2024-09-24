print("Ovo je Python skripta u Laravel projektu.")

from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from webdriver_manager.chrome import ChromeDriverManager
import time

# Konfiguracija Chrome-a sa WebDriverManager-om
options = webdriver.ChromeOptions()
options.add_argument("--headless")
options.add_argument("--disable-gpu")
options.add_argument("--disable-software-rasterizer")
options.add_argument("--disable-dev-shm-usage")
options.add_argument("--no-sandbox")
options.add_argument("--remote-debugging-port=9222")
options.add_argument("user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36")



# Pokretanje Chrome drivera sa određenom verzijom
driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()), options=options)


# Otvaranje URL-a
driver.get("https://www.realestate.com.au/agent/ted-pye-3233288")

cookies = [
    {
        'name': 'KP_UIDz', 'value': '0IfNYSXWBxMPOmbSZkdFU2rQhQs0ZqSFgGuB37qZevUwW66o55L0cnl2kGfXsdsV2XnSCRqEbuCcJdiPuMq2h6qKHxFRSx01XsJKgwsMIOW00zOc0muSGM2f5YpSn8ytYhCNL1oztUQfFTUqjqXmAmr4yQI59FJEzg0n4Hgy', 'domain': 'www.realestate.com.au', 'path': '/', 'expiry': 1726165107, 'secure': False, 'httpOnly': True, 'sameSite': 'None'
    },
    # Ostali kolačići...
    {
        'name': 'KP_UIDz-ssn', 'value': '0IfNYSXWBxMPOmbSZkdFU2rQhQs0ZqSFgGuB37qZevUwW66o55L0cnl2kGfXsdsV2XnSCRqEbuCcJdiPuMq2h6qKHxFRSx01XsJKgwsMIOW00zOc0muSGM2f5YpSn8ytYhCNL1oztUQfFTUqjqXmAmr4yQI59FJEzg0n4Hgy', 'domain': 'www.realestate.com.au', 'path': '/', 'expiry': 1726165107, 'secure': True, 'httpOnly': True, 'sameSite': 'None'
    }
]

# Dodaj kolačiće u sesiju
for cookie in cookies:
    driver.add_cookie(cookie)

# Pusti malo vremena da se stranica učita
time.sleep(5)  # Povećano čekanje

driver.execute_script("window.scrollTo(0, document.body.scrollHeight);")
time.sleep(2)

# Čekaj dok se stranica potpuno ne učita
while True:
    if driver.execute_script("return document.readyState") == "complete":
        break

print("Stranica je potpuno učitana!")

scripts = driver.find_elements(By.TAG_NAME, "script")
for script in scripts:
    src = script.get_attribute("src")
    print(src)
else:
    print("Skripta nije učitana.")


try:
    captcha = driver.find_element(By.XPATH, "//*[contains(@class, 'captcha')]")
    print("CAPTCHA element pronađen.")
except Exception:
    print("CAPTCHA element nije pronađen.")

iframes = driver.find_elements(By.TAG_NAME, "iframe")
if iframes:
    print("Iframe pronađen, moguće je da CAPTCHA koristi iframe.")
else:
    print("Iframe nije pronađen.")

html_body = driver.page_source
print(html_body)

# Dohvati element po klasi uz dinamičko čekanje
try:
    agent_name_element = driver.find_element(By.CLASS_NAME, "styles__AgentName-sc-1ifcqm-7")
    print(f"Agent name found: {agent_name_element.text}")
except Exception as e:
    print(f"Element nije pronađen: {e}")

# Zatvori browser
driver.quit()
