using UnityEngine;
using System.Collections;
using System.Net;

/// <summary>
/// Contains all logic which has to do with internet access.
/// </summary>
public class NetworkManager {

    private static bool isConnected = false;
    public static string URL = "https://www.google.be";

    public static IEnumerator CheckInternetConnection(string IPaddress)
    {
        WWW www = new WWW(IPaddress);
        yield return www;

        if (www.error == null)      // There is no problem accessing the webpage
        {
            // so we can assume we are connected to the internet
            isConnected = true;

            // Then we need to check at wich location we are
            LocationManager.DetermineLocation();
        }
        else
        {
            isConnected = false;
            UIHandler.errorM.text = "We kunnen geen contact met je leggen.\r\nMaak alsjeblief verbinding met het internet.";
            UIHandler.CheckForError();
        }
    }

    /// <summary>
    /// Is the device connected to the internet or not?
    /// </summary>
    public static bool IsConnected
    {
        get { return isConnected; }
    }
}
