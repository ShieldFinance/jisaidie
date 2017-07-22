
## About Jisaidie

An app for mobile loan processing

## Installing

- Create a database from jisaidie.sql and set the necessary database access codes in .env

## Api Endpoints

There is only one api endpoints that processes all the requests: /api/serviceRequest. It accepts the following parameters

- **request** Holds a json string containg the request payload eg 
```json
{
	"first_name": "John",
	"middle_name": "Doe",
	"surname": "Doe",
	"email ": "example@example.com",
	"mobile_number": "254723383855",
	"activation_code": 555556y7,"id_number":"25125368"
}
```
- **action** This is the action that will be performed on the payload. Eg **UpdateCustomerActivationCode**

## Available actions.
- **CreateCustomer** Creates a new customer from the json payload. at the minimum, you must provide device_id and mobile_number 
**Available fields**
  - first_name
  - last_name
  - surname
  - email
  - mobile_number
  - id_number
  - device_id
- **UpdateCustomerProfile** Update customer details
**Available fields**
  - first_name
  - last_name
  - surname
  - email
  - mobile_number
  - id_number
  - device_id

- **UpdateCustomerActivationCode** Update or add customer activation code
**Available fields**
  - activation_code
  - mobile_number
- **ActivateCustomer** Activate customer
**Available fields**
  - activation_code
  - mobile_number
  
- **FetchScreens** Fetch app screens (get method), 


## Response codes and customer status
Check configurations on app config