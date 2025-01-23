
# Billing System MaxCloud

The system will utilize Laravel's features to meet the following requirements:

**1** : Customers must have sufficient balance to create a VPS.

**2** : Billing will commence immediately after the VPS is successfully created.

**3** : The basic components of the VPS include CPU, RAM, and Storage.

**4** : A check will be performed every hour to calculate the VPS uptime.

**5** : Regular checks will ensure the total service cost for the month and the remaining balance to confirm the balance is sufficient.

**6** : If the remaining balance is less than 10% of the total service cost, the customer will receive a notification that their balance is low.

**7** : If the balance is negative, the customer will be suspended.


## ERD Billing System MaxCloud

![App Screenshot](https://i.ibb.co.com/tc5K4TN/Cloud-Max-Billing-Sistem-3.jpg)




## API Reference

### Get All Users

```http
  GET /api/users
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `No Need` |  |  |

### Register

```http
  POST /api/register
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `name`      | `string` | Required |
| `email`      | `string` | Required, Uniq |
| `name`      | `string` | Required, min: 6 |

**Explain:**  
When a user registers, a wallet (user balance) is automatically created.

### Login

```http
  POST /api/login
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `name`      | `string` | Required |
| `email`      | `string` | Required, Uniq |
| `name`      | `string` | Required, min: 6 |

**Explain:**  
Token is available every 24 hours

### Get Wallet

```http
  GET /api/wallet
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `No Need`      || | |

### Update Wallet

```http
  PUT /api/wallet
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `headers`      | `Authorization` | Required |
| `name`      | `string` | Required |
| `email`      | `string` | Required, Uniq |
| `name`      | `string` | Required, min: 6 |

**Explain:**  
To access this endpoint you need Token as authentication.

### Get VPS

```http
  GET /api/vps
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `No Need`      || | |

### Get Vps By Id

```http
  GET /api/vps/{id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `params` | Required |
| `name`      | `string` | Required |
| `email`      | `string` | Required, Uniq |
| `name`      | `string` | Required, min: 6 |

### Create VPS

```http
  POST /api/vps
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `cpu`      | `string` | Required |
| `ram`      | `string` | Required |
| `storage`      | `string` | Required |
| `price`      | `integer` | Required |

### Update VPS

```http
  PUT /api/vps/{id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `Params` | Required |
| `cpu`      | `string` | Sometimes |
| `ram`      | `string` | Sometimes |
| `storage`      | `string` | Sometimes  |
| `price`      | `integer` | Sometimes  |

**Explain:**  
Can update for some field or single field.

### Delete VPS

```http
  DELETE /api/vps/{id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `Params` | Required |

### Get All Transaction

```http
  GET /api/transaction
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `No Need`      || | |

### Create Transaction

```http
  POST /api/transaction
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `headers`      | `Authorization` | Required |
| `vps_id`      | `integer` | Required |

**Explain:**  
To create transaction you need Token as authentication.
And when you want pick vps from VPS table you gonna use vps_id.

### Update Transaction

```http
  PUT /api/transaction
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `headers`      | `Authorization` | Required |
| `vps_id`      | `integer` | Required |

**Explain:**  
To update transaction you need Token as authentication.
And when you want pick vps from VPS table you gonna use vps_id.

### Update Transaction Every Hours

```http
  PUT /api/update-transactions
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `headers`      | `Authorization` | Required |

**Explain:**  
This is used to hit the API every hour. and utilizes the updated_at colomn as Uptime.

### Delete Transaction Every Hours

```http
  DELETE /api/transaction/{id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `headers`      | `Authorization` | Required |

**Explain:**  
Don't forget use token for delete transaction.

## Explain about table ERD

### User
| User |
|------|
| Id (PK)  |
| Name  |
| Email | 
| Password | 

This table standart user login/register

### Wallet
| Wallets |
|------|
| Id (PK, FK)  |
| balance | 

This table is used to determine the amount of funds for certain users with a one-to-one relationship.

### VPS
| VPS |
|------|
| Id (PK)  |
| Cpu | 
| Ram | 
| Storage | 
| Price | 

This table defines a VPS Product where there are standard specifications and additional Price. now here the Price in question is Price/hours.

### Transaction
| Transaction |
|------|
| Id (PK)  |
| Amount | 
| Type | 
| Wallet_id (FK) | 
| Vps_id (FK) | 

So this transaction table intends to accommodate usage on the vps. Here there is an amount that is a field to calculate how much the total usage of the VPS is for so many hours.  
Which then when the amount increases it corresponds to the price/hours of Vps_id and will reduce the balance of wallet_id also based on the price/hours of vps_id.
## Installation

**Clone project from github**

```bash
  git clone https://github.com/iqbalpradipta/Billing-System-MC.git
```

**Install Depedency**

```bash
  composer install
```

**Setting .env**  
You can using .env.example and don't forget generate SECRET_JWT
```bash
  php artisan jwt:secret
```

**Migration**

```bash
  php artisan migrate
```

**Postman**  
You can export my postman and using it.  

```bash
  name file: MaxCloud BillingSystem.postman_collection.json
```
