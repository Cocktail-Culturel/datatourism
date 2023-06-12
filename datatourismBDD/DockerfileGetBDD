FROM python:3.9-slim

WORKDIR /setup

COPY requirements.txt .

COPY getBDD.py .

RUN pip install -r requirements.txt

ENTRYPOINT [ "python3","getBDD.py"]

LABEL version="3.0" maintainer="TSE"