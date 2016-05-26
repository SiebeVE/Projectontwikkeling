#Project A

##API
API-url: `https://teamgctof.multimediatechnology.be/api`

###Authentication
_Fetch a JSON Web Token_  
####GET `/getToken`
|Parameters  |                          |  
| ---------- | ------------------------- |  
| username|[username]|  
| password|[password]|  
**Response**  
```json
{
"status":"[ok|error]",
"token":"[token]",
"error":"[errorText]"
}
```
*Status is either ok or error and error is only available if there is an error*
