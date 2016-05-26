using LitJson;
using System;
using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public enum MapType
{
    roadmap = 0,
    satellite = 1,
    terrain = 2
}

public enum MarkerColors
{
    brown,
    green,
    purple,
    yellow,
    blue,
    orange,
    red
}

public static class MapManager {

    private static string mURLaddress, STATUS = "status", OK_STATUS_CODE = "OK";
    private static WWW www;
    private static int zoom = 12;
    private static MapType maptype;
    private static List<Project> tempProjects = new List<Project>();

    public static string API_KEY = "&key=AIzaSyAn26km9c6rfD7sWftyRa29QjwISSsIF9I";
    public static char[] alphabet = new char[25] { 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z' };
    private static float searchedLat, searchedLong;

    #region Properties
    public static string URLaddress
    {
        get { return mURLaddress; }
        set { mURLaddress = value; }
    }

    public static WWW Www
    {
        get { return www; }
        set { www = value; }
    }

    /// <summary>
    /// The zoom factor of the map, how much should the map be zoomed in?
    /// </summary>
    /// <remarks>Value range from 1 (world level) to 20 (Buildings level)</remarks>
    public static int Zoom
    {
        get { return zoom; }
        set { zoom = value; }
    }

    /// <summary>
    /// Which type of map do we want to be showed?
    /// </summary>
    public static MapType Maptype
    {
        get { return maptype; }
        set { maptype = value; }
    }
    #endregion 

    /// <summary>
    /// Loads the map from Google into the application.
    /// </summary>
    /// <param name="URL">The URL which has been set in the SetAddress function.</param>
    /// <returns></returns>
    public static IEnumerator LoadMap(string URL)
    {
        www = new WWW(URL);

        while (!www.isDone)
        {
            UIHandler.map.texture = Resources.Load<Sprite>("Images/Error/Laden").texture;
        }

        yield return www;

        if (!string.IsNullOrEmpty(www.error))
        {
            UIHandler.map.texture = Resources.Load<Sprite>("Images/Error/Error").texture;
        }
        else
        {
            UIHandler.map.texture = www.texture;
        }
    }

    /// <summary>
    /// This method creates a URL which depends on the current location. (Runs at startup.)
    /// </summary>
    /// <param name="location">The location the user wants to find.</param>
    public static void SetAddress(string location, List<Project> projects)
    {
        mURLaddress = "http://maps.googleapis.com/maps/api/staticmap?center=";

        // The user has entered no input or the app starts for the first time
        if (location == string.Empty)
        {
            mURLaddress += LocationManager.Latitude + "," + LocationManager.Longitude + "&zoom=" + zoom + "&maptype=" + maptype + "&markers=color:red%7Clabel:A%7C" + LocationManager.Latitude + "," + LocationManager.Longitude;
            AddProjects(projects, LocationManager.Latitude, LocationManager.Longitude);
        }
        else if(location != string.Empty)
        {
            // Add desired location
            mURLaddress += location + "&zoom=" + zoom + "&maptype=" + maptype + "&markers=color:red%7Clabel:A%7C" + location;
            AddProjects(projects, searchedLat, searchedLong);
        }

        // at last add the size and API key
        mURLaddress += "&size=1920x1080" + API_KEY;

        if (UIHandler.sceneName == "Maps")
        {
            MapLoader.CallLoadMap();

            // set the searched values back to zero to prevent this method from running
            searchedLat = 0;
            searchedLong = 0;
        }
    }

    /// <summary>
    /// Adds all the projects in the url parameters, so the markers will be displayed on the correct location..
    /// </summary>
    /// <param name="projects">The list of projects.</param>
    /// <param name="lat">Is it the latitude of the current location or a searched one?</param>
    /// <param name="lon">Is it the longitude of the current location or a searched one?</param>
    private static void AddProjects(List<Project> projects, float lat, float lon)
    {
        System.Random r = new System.Random();
        var currentIndex = 0;

        for(byte i = 0; i < projects.Count; i++)
        {
            if((int)CalculateDistanceProjects(projects[i].Latitude, projects[i].Longitude, lat, lon) <= PlayerPrefs.GetInt("radius"))
            {
                mURLaddress += "&markers=color:" + (MarkerColors)r.Next(0, Enum.GetValues(typeof(MarkerColors)).Length) + "%7Clabel:" + alphabet[currentIndex] + "%7C" + projects[i].Latitude + "," + projects[i].Longitude;
                currentIndex++;
            }
        }
    }

    /// <summary>
    /// Returns the latitude and longitude coordinates of a specified location
    /// </summary>
    /// <param name="location"></param>
    /// <returns>The latitude and longitude of the prompted location.</returns>
    /// <param name="projects">The projectManager projects list.</param>
    public static IEnumerator ReturnLatLong(string location, List<Project> projects)
    {
        location = location.Replace(" ", "%20");

        WWW www = new WWW("https://maps.googleapis.com/maps/api/geocode/json?address=" + location + API_KEY);
        yield return www;

        if(www.error == null)
        {
            JsonData dataLatLong = JsonMapper.ToObject(www.text);

            if (dataLatLong[STATUS].ToString() == OK_STATUS_CODE)
            {
                double[] tempArray = GetLatLon(dataLatLong["results"][0]["geometry"]["location"]);

                searchedLat = (float)tempArray[0];
                searchedLong = (float)tempArray[1];

                if (searchedLat != 0 && searchedLong != 0)
                {
                    SetAddress(location, projects);
                }
            }
        }
    }

    public static IEnumerator ReturnLocationName(string input, Text location_string)
    {
        // we haven't searched for a location
        if (input == string.Empty)
        {
            // so we make a URL and return a json containing the information we need
            WWW www = new WWW("https://maps.googleapis.com/maps/api/geocode/json?latlng=" + LocationManager.Latitude + "," + LocationManager.Longitude + "&result_type=street_address" + API_KEY);
            yield return www;

            // there was no error
            if (www.error == null)
            {
                // read data from json file
                JsonData data = JsonMapper.ToObject(www.text);

                // Google returned OK status code, so we have at least one address
                if (data[STATUS].ToString() == OK_STATUS_CODE)
                {
                    location_string.text = GetData(data, "formatted_address");
                }
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
    private static string GetData(JsonData dataObject, string searchForData)
    {
        string result = dataObject["results"][0][searchForData].ToString();
        return result.Substring(0, result.LastIndexOf(','));
    }

    /// <summary>
    /// Gets the latitude and longitude of a given location.
    /// </summary>
    /// <param name="dataObj">The JSON object array where the coordinates are stored.</param>
    /// <returns>A float array containing the coordinates.</returns>
    private static double[] GetLatLon(JsonData dataObj)
    {
        double[] temp = new double[2];

        for(byte i = 0; i < dataObj.Count; i++)
        {
            temp[i] = (double)dataObj[i];
        }

        return temp;
    }

    /// <summary>
    /// Calculates the distance between the desired location and every project in the list.
    /// </summary>
    /// <param name="lat1">Latitude of the project</param>
    /// <param name="long1">Longitude of the project</param>
    /// <param name="lat2">Latitude of the location</param>
    /// <param name="long2">Longitude of the location</param>
    /// <returns>The distance between the project and the desired location</returns>
    private static float CalculateDistanceProjects(float lat1, float long1, float lat2, float long2)
    {
        float theta = long1 - long2;
        float factor1 = Mathf.Sin(lat1 * Mathf.Deg2Rad), factor2 = Mathf.Sin(lat2 * Mathf.Deg2Rad), factor3 = Mathf.Cos(lat1 * Mathf.Deg2Rad), factor4 = Mathf.Cos(lat2 * Mathf.Deg2Rad), factor5 = Mathf.Cos(theta * Mathf.Deg2Rad);
        float dist = factor1 * factor2 + factor3 * factor4 * factor5;

        dist = Mathf.Acos(dist);
        dist *= Mathf.Rad2Deg;
        dist *= 60 * 1.1515f;

        dist *= 1.609344f;  // in kilometres

        return dist;
    }
}
