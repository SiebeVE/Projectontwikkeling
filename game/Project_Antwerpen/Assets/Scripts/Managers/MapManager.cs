using UnityEngine;
using System.Collections;

public class MapManager {

    private static string mURLaddress = "http://maps.googleapis.com/maps/api/staticmap?center=";
    private static string API_KEY = "&key=AIzaSyAn26km9c6rfD7sWftyRa29QjwISSsIF9I";
    private static WWW www;

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

    public static IEnumerator LoadMap(string URL)
    {
        www = new WWW(URL);
        yield return www;
        test.i.GetComponent<Renderer>().material.mainTexture = www.texture;
    }

    public static void SetAddress()
    {
        mURLaddress += LocationManager.Latitude + "," + LocationManager.Longitude + "&zoom=13&maptype=satellite&markers=color:red%7Clabel:A%7C" + LocationManager.Latitude + "," + LocationManager.Longitude + "&size=1920x1080" + API_KEY;
        Debug.Log(mURLaddress);
    }
}
