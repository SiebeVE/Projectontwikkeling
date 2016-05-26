using UnityEngine;
using System;
using System.Collections;
using System.Collections.Generic;

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

public class MapManager {

    private static string mURLaddress;
    public static string API_KEY = "&key=AIzaSyAn26km9c6rfD7sWftyRa29QjwISSsIF9I";
    private static WWW www;
    private static int zoom = 12;
    private static MapType maptype;

    public static char[] alphabet = new char[25] { 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z' };

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
        }
        else if(location != string.Empty)
        {
            // Add desired location
            mURLaddress += location + "&zoom=" + zoom + "&maptype=" + maptype + "&markers=color:red%7Clabel:A%7C" + location;
        }

        // in both cases we want the markers to be displayed
        AddProjects(projects);

        // at last add the size and API key
        mURLaddress += "&size=1920x1080" + API_KEY;
    }

    /// <summary>
    /// Adds all the projects in the url parameters, so the markers will be displayed on the correct location..
    /// </summary>
    /// <param name="projects">The list of projects.</param>
    private static void AddProjects(List<Project> projects)
    {
        System.Random r = new System.Random();

        for(byte i = 0; i < projects.Count; i++)
        {
            mURLaddress += "&markers=color:" + (MarkerColors)r.Next(0, Enum.GetValues(typeof(MarkerColors)).Length) + "%7Clabel:" + alphabet[i] + "%7C" + projects[i].Latitude + "," + projects[i].Longitude;
        }
    }
}
