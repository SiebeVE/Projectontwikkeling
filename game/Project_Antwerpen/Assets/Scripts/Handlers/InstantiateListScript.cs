using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using LitJson;

/// <summary>
/// Instantiates the list of buttons in the map screen.
/// </summary>
public static class InstantiateListScript {

    private static string STATUS = "status", OK_STATUS_CODE = "OK";

    /// <summary>
    /// Instantiates a button of the current project in the map list.
    /// </summary>
    /// <param name="go">The button to be instantiated</param>
    /// <param name="parent">The parent where the buttons will be instantiated</param>
    /// <param name="projects">The list of projects</param>
    public static void CreateListItemInstance(GameObject go, GameObject parent, List<Project> projects)
    {
        for(byte i = 0; i < projects.Count; i++)
        {
            var instance = Object.Instantiate(go);

            instance.name = projects[i].Name;
            instance.transform.Find("Letter").GetComponent<Text>().text = MapManager.alphabet[i].ToString();
            instance.transform.Find("Lbl").GetComponent<Text>().text = projects[i].Name;

            instance.transform.SetParent(parent.transform, false);
        }
    }

    /// <summary>
    /// Returns the current location in readable format.
    /// </summary>
    /// <param name="input">The input of the textfield in the maps menu</param>
    /// <param name="location_string">The text component in the listview</param>
    /// <returns></returns>
    public static IEnumerator ReturnLocationName(string input, Text location_string)
    {
        // we haven't searched for a location
        if(input == string.Empty)
        {
            // so we make a URL and return a json containing the information we need
            WWW www = new WWW("https://maps.googleapis.com/maps/api/geocode/json?latlng=" + LocationManager.Latitude + "," + LocationManager.Longitude + "&result_type=street_address" + MapManager.API_KEY);
            yield return www;

            // there was no error
            if (www.error == null)
            {
                // read data from json file
                JsonData data = JsonMapper.ToObject(www.text);

                // Google returned OK status code, so we have at least one address
                if (data[STATUS].ToString() == OK_STATUS_CODE)
                {
                    location_string.text = GetData(data, "results", "long_name");
                }
                //Debug.Log(data["results"][0][0][1]["long_name"] + " " + data["results"][0][0][0]["long_name"] + ", " + data["results"][0][0][2]["long_name"]);
                else
                {
                    location_string.text = "Geen locatie";
                }
            }
        }
        else    // we have searched for a location
        {
            // there's no need to search the location name because it's already filled in by the user
            location_string.text = input;
        }
    }

    /// <summary>
    /// Returns the geolocation in string format.
    /// </summary>
    /// <param name="dataObject">The json data file</param>
    /// <param name="searchForParent">The array (parent object) where the variable is located</param>
    /// <param name="searchForData">The value we want to collect</param>
    /// <returns></returns>
    private static string GetData(JsonData dataObject, string searchForParent, string searchForData)
    {
        return string.Format(dataObject[searchForParent][0][0][1][searchForData] + " " + dataObject[searchForParent][0][0][0][searchForData] + ", " + dataObject[searchForParent][0][0][2][searchForData]);
    }
}
