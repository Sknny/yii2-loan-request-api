# Loan Request API

## Description
Loan Application API is a simple API built with the Yii2 framework, designed for managing loan requests. The project includes two main endpoints: one for submitting loan requests and another for processing them with specific business logic. The API ensures that business rules are followed, such as limiting the number of approved loans per user and handling requests in parallel using `spatie/async`

## Installation

1. Run containers
```bash
git clone https://github.com/Sknny/yii2-loan-request-api.git
cd yii2-loan-request-api
docker-compose up -d
```

2. Install dependencies using Composer
```bash
cd src
composer install
```

3. Apply migrations
```bash
php yii migrate
```

## Usage:

1.	Submit Loan Requests:

`POST [/request]`

- Accepts loan request data in JSON format:
```json
{
  "user_id": 1,
  "amount": 3000,
  "term": 30
}
```
- Validates the request and stores it in the database
- Ensures users cannot submit new requests if they already have an approved loan

2.	Process Loan Requests

`GET [/processor?delay=5]`

- Randomly determines the status of loan requests (approved or declined), with a 10% approval probability
- Uses database locks to ensure no two requests for the same user are approved simultaneously
- Simulates decision delays using the sleep function based on the delay parameter

---

<a href="https://www.yiiframework.com/" rel="nofollow">
<img src="https://camo.githubusercontent.com/089ef5bd063e38321bc156add1711296f35ba9be0212666ad013938a994ece22/68747470733a2f2f7777772e7969696672616d65776f726b2e636f6d2f696d6167652f7969695f6c6f676f5f6c696768742e737667" width="400" alt="Yii Framework" data-canonical-src="https://www.yiiframework.com/image/yii_logo_light.svg" style="max-width: 100%;">
</a>
