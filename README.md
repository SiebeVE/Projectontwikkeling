#Project A

##API
API-url: `https://teamgctof.multimediatechnology.be/api`
>For accessing the API you need the `[appSecret]`

###Authentication
_Fetch a JSON Web Token and the user_  
_Check to see if possible to create own jwt in unity_
####GET `/get/login`
| Parameters  | |  
| ---- | ---- |  
|email|[string email]|  
|password|[string password]|
|secret|[string appSecret]|
**Response**  
*Success*
```json
{
"status":"ok",
"token":"[string token]",
"user":{
  "id": "[int id]",
  "email": "[string email]",
  "firstname": "[string firstname]",
  "lastname": "[string lastname",
  "postal_code": "[string postalCode]",
  "city": "[string city]",
  "is_admin": "[tinyint isAdmin]",
  "created_at": "[date created_at]",
  "updated_at": "[date updated_at]"
  },
}
```
*Failure*
```json
{
"status":"error",
"error": "[string errorText]"
}
```

###Project
_Fetch all the projects and phases_  
####GET `/get/projects`  
| Parameters  | |  
| ---- | ---- |  
|secret|[string appSecret]|  
**Response**  
*Success*
```json
"status": "ok",
"projects": [
  {
    "id": "[int projectId]",
    "name": "[string projectName]",
    "description": "[string projectDescription]",
    "address": "[string projectAddress]",
    "photo_path": "[string projectRelativePathToPicture]",
    "photo_left_offset": "[string projectPictureOffset (Ex.: -12px)]",
    "latitude": "[string projectLatitude]",
    "longitude": "[string projectLongitude]",
    "created_at": "[date createProjectdAt]",
    "updated_at": "[date updatedProjectAt]",
    "phases": [
      {
        "id": "[int phaseId]",
        "name": "[string phaseName]",
        "description": "[string description]",
        "start": "[date startDatePhase]",
        "end": "[date endDatePhase]",
        "parentHeight": "[string heightOfContainer (Ex.: 255px)]",
        "created_at": "[date createPhaseAt]",
        "updated_at": "[date updatedPhaseAt]",
      }
    ]
  }
]
```
*Failure*
```json
{
"status":"error",
"error": "[string errorText]"
}
```

###Ignored Words
_Add or remove words for statistics_  
####POST `/post/statistics/word`

| Parameters |        |  
| ---------- | ------ |  
|word|[string newWord]|

**Authorization**

| Header | Content |
| ---- | ----|
|Authorization|Bearer [string token]|

_Token is a JWT_  
>This is a refresh token, so in a success response their is a new Authorization header  

**Response**  
*Success*
```json
"status": "ok"
```
| Header | Content |
| ---- | ----|
|Authorization|Bearer [string token]|
*Failure*
```json
{
"status":"error",
"error": "[string errorText]"
}
```
