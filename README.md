#Project A

##API
API-url: `https://teamgctof.multimediatechnology.be/api`
>For accessing the API you need the `[appSecret]`

###Authentication
_Fetch a JSON Web Token_  
_Check to see if possible to create own jwt in unity_
####POST `/getToken`
|Parameters| |  
| ---- | ---- | 
|username|[username]|  
|password|[password]|
|secret|[appSecret]|  

**Response**  
```json
{
"status":"[ok|error]",
"token":"[token]",
"error":"[errorText]"
}
```
_Status is either ok or error and error is only available if there is an error_
