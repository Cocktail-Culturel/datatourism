from time import sleep
import requests
import json

with open('flux.json') as file:
    json_data = json.load(file)
    
for region, url in json_data.items():
    print("downloading...")
    url += "bb3e487f-7b9f-4085-b409-a530cbe2fb90"
    try:
        rdf_data = requests.get(url).content
        print(f"{region} flux downloaded")
    except requests.exceptions.RequestException as err:
        print(f"Error : {err}")
    
    sleep(1)
    print("sending...")
    blazegraph_url = "http://localhost:9999/blazegraph/namespace/kb/sparql"
    headers = {"Content-Type": "application/rdf+xml"}
    try:
        response = requests.post(blazegraph_url, data=rdf_data, headers=headers)
        print("Response Status Code:", response.status_code)
    except requests.exceptions.RequestException as err:
        print(f"Error : {err} Staus code : {response.status_code}")
    sleep(1)