from time import sleep
import requests
import json
from datetime import datetime

log_file = open('log.txt', 'a')

with open('flux.json') as file:
    json_data = json.load(file)

for region, url in json_data.items():
    current_time = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    log_file.write(f"[{current_time}] Downloading {region} flux\n")
    url += "bb3e487f-7b9f-4085-b409-a530cbe2fb90"
    try:
        rdf_data = requests.get(url).content
        log_file.write(f"[{current_time}] {region} flux downloaded\n")
    except requests.exceptions.RequestException as err:
        log_file.write(f"[{current_time}] Error: {err}\n")

    sleep(3)
    current_time = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    log_file.write(f"[{current_time}] Loading {region} in the database\n")
    
    try:
        blazegraph_url = "http://localhost:9999/blazegraph/namespace/kb/sparql"
        headers = {"Content-Type": "application/rdf+xml"}
        response = requests.post(blazegraph_url, data=rdf_data, headers=headers)
        log_file.write(f"[{current_time}] Load finished with status code: {response.status_code}\n")
    except requests.exceptions.RequestException as err:
        log_file.write(f"[{current_time}] Error: {err} Status code: {response.status_code}\n")

    sleep(2)

log_file.close()
