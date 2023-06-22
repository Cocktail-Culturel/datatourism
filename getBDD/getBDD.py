# from time import sleep
# import requests
# import json
# from datetime import datetime

# #log_file = open('log.txt', 'a')

# with open('flux.json') as file:
#     json_data = json.load(file)

# for region, url in json_data.items():
#     #current_time = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
#     #log_file.write(f"[{current_time}] Downloading {region} flux\n")
#     print(f"Downloading {region}")
#     url += "bb3e487f-7b9f-4085-b409-a530cbe2fb90"
#     try:
#         rdf_data = requests.get(url).content
#         #log_file.write(f"[{current_time}] {region} flux downloaded\n")
#         print("Downloaded")
#     except requests.exceptions.RequestException as err:
#         #log_file.write(f"[{current_time}] Error: {err}\n")
#         print(err)

#     sleep(3)
#     #current_time = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
#     #log_file.write(f"[{current_time}] Loading {region} in the database\n")
#     print("Loading in DB")
    
#     try:
#         blazegraph_url = "http://localhost:9999/blazegraph/namespace/kb/sparql"
#         headers = {
#             #'User-Agent': 'My User Agent 1.0',
#             'Content-Type': 'application/rdf+xml'
#             }
#         response = requests.post(blazegraph_url, data=rdf_data, headers=headers)
#         #log_file.write(f"[{current_time}] Load finished with status code: {response.status_code}\n")
#         print("loaded")
#     except requests.exceptions.RequestException as err:
#         #log_file.write(f"[{current_time}] Error: {err} Status code: {response.status_code}\n")
#         print(err)

#     sleep(3)

#log_file.close()

import requests

print("downloading...")
rdf_url = "https://diffuseur.datatourisme.fr/webservice/37bc3e10f044c1d393d523b99482ad3c/bb3e487f-7b9f-4085-b409-a530cbe2fb90"
rdf_data = requests.get(rdf_url).content

print("sending...")
blazegraph_url = "https://datatourism-bdd.cocktail-culturel.com/blazegraph/namespace/kb/sparql"
headers = {"Content-Type": "application/rdf+xml"}
response = requests.post(blazegraph_url, data=rdf_data, headers=headers)


print("Response Status Code:", response.status_code)
