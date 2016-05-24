using UnityEngine;
using System.Collections;

public enum MapType
{
    roadmap = 0,
    satellite = 1,
    terrain = 2
}

public enum MarkerColors
{
    black,
    brown,
    green,
    purple,
    yellow,
    blue,
    gray,
    orange,
    red,
    white
}

public class MapManager {

    private static string mURLaddress, API_KEY = "&key=AIzaSyAn26km9c6rfD7sWftyRa29QjwISSsIF9I";
    private static WWW www;
    private static int zoom = 12;
    private static MapType maptype;

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
    public static void SetAddress(string location)
    {
        mURLaddress = "http://maps.googleapis.com/maps/api/staticmap?center=";

        // The user has entered no input or the app starts for the first time
        if (location == string.Empty)
        {
            mURLaddress += LocationManager.Latitude + "," + LocationManager.Longitude + "&zoom=" + zoom + "&maptype=" + maptype + "&markers=color:red%7Clabel:A%7C" + LocationManager.Latitude + "," + LocationManager.Longitude + "&size=1920x1080" + API_KEY;
        }
        else if(location != string.Empty)
        {
            // Add desired location
            mURLaddress += location + "&zoom=" + zoom + "&maptype=" + maptype + "&markers=color:red%7Clabel:A%7C" + location + "&size=1920x1080" + API_KEY;
        }
    }
}
