import requests
from bs4 import BeautifulSoup

url = "https://fr.indeed.com/"

response = requests.get(url)
soup = BeautifulSoup(response.content, "html.parser")


# Extraire les URLs des ancres des liens
anchors = soup.find_all("a", href=True)
anchor_urls = [a["href"] for a in anchors]
print("URLs des ancres des liens :")
for anchor_url in anchor_urls:
    print(anchor_url)


buttons = soup.find_all("button", {"type": "button"})
button_urls = [button.get("onclick") for button in buttons if button.get("onclick")]
print("\nURLs des boutons de redirection :")
for button_url in button_urls:
    print(button_url)
