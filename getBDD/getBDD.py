import requests

print("downloading...")
rdf_url = "https://diffuseur.datatourisme.fr/webservice/37bc3e10f044c1d393d523b99482ad3c/bb3e487f-7b9f-4085-b409-a530cbe2fb90"
rdf_data = requests.get(rdf_url).content


print("sending...")
blazegraph_url = "http://localhost:9999/blazegraph/namespace/kb/sparql"
headers = {"Content-Type": "application/rdf+xml"}
response = requests.post(blazegraph_url, data=rdf_data, headers=headers)


print("Response Status Code:", response.status_code)
