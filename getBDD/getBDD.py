from time import sleep
import requests
import json
from datetime import datetime

log_file = open('log.txt', 'a')

with open('flux.json') as file:
    json_data = json.load(file)

for region, url in json_data.items():
    log_file.write(f"[{datetime.now()}] Downloading {region} flux\n")
    url += "bb3e487f-7b9f-4085-b409-a530cbe2fb90"
    try:
        rdf_data = requests.get(url).content
        log_file.write(f"[{datetime.now()}] {region} flux downloaded\n")
    except requests.exceptions.RequestException as err:
        log_file.write(f"[{datetime.now()}] Error: {err}\n")

    sleep(3)
    log_file.write(f"[{datetime.now()}] Loading {region} in the database\n")
    blazegraph_url = "http://localhost:9999/blazegraph/namespace/kb/sparql"
    headers = {"Content-Type": "application/rdf+xml"}
    try:
        response = requests.post(blazegraph_url, data=rdf_data, headers=headers)
        log_file.write(f"[{datetime.now()}] Load finished with status code: {response.status_code}\n")
    except requests.exceptions.RequestException as err:
        log_file.write(f"[{datetime.now()}] Error: {err} Status code: {response.status_code}\n")

    sleep(2)

log_file.close()
