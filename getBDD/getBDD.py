import requests
import json

with open('flux.json') as file:
    json_data = json.load(file)
    
for region, url in json_data.items():
    print("downloading...")
    url += "bb3e487f-7b9f-4085-b409-a530cbe2fb90"
    print(url)
    rdf_data = requests.get(url).content

    print("sending...")
    blazegraph_url = "http://localhost:9999/blazegraph/namespace/kb/sparql"
    headers = {"Content-Type": "application/rdf+xml"}
    response = requests.post(blazegraph_url, data=rdf_data, headers=headers)


print("Response Status Code:", response.status_code)
