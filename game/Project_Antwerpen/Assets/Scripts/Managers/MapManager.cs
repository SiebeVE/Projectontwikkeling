using UnityEngine;
using System.Collections;

public enum MapType
{
    roadmap,
    satellite,
    terrain,
    hybrid
}

public class MapManager {

    private static string mURLaddress = "http://maps.googleapis.com/maps/api/staticmap?center=", API_KEY = "&key=AIzaSyAn26km9c6rfD7sWftyRa29QjwISSsIF9I";
    private static WWW www;
    private static int zoom, scale;
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
    /// The scale factor of the map, how much detail we want to be showed in the map?
    /// </summary>
    /// <remarks>Only value of 1 or 2 is supported in the free version.</remarks>
    public static int Scale
    {
        get { return scale; }
        set { scale = value; }
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
        yield return www;
        test.i.GetComponent<Renderer>().material.mainTexture = www.texture;
    }

    /// <summary>
    /// This method creates a URL which depends on the current location. (Runs at startup.)
    /// </summary>
    public static void SetAddress()
    {
        mURLaddress += LocationManager.Latitude + "," + LocationManager.Longitude + "&zoom=13&maptype=roadmap&markers=color:red%7Clabel:A%7C" + LocationManager.Latitude + "," + LocationManager.Longitude + "&size=1920x1080" + API_KEY;
    }
}
