﻿using UnityEngine;
using System.Collections;
using System.Net;

/// <summary>
/// Contains all logic which has to do with internet access.
/// </summary>
public class NetworkManager {

    private static bool isConnected = false;
    public static string URL = "https://teamgctof.multimediatechnology.be/";
    public static string ping = "https://www.google.be";

    public static IEnumerator CheckInternetConnection(string IPaddress)
    {
        WWW www = new WWW(IPaddress);
        yield return www;

        if (www.error == null)      // There is no problem accessing the webpage
        {
            // so we can assume we are connected to the internet
            isConnected = true;
        }
        else
        {
            isConnected = false;
        }

        // Then we need to check at wich location we are
        LocationManager.DetermineLocation();
    }

    /// <summary>
    /// Is the device connected to the internet or not?
    /// </summary>
    public static bool IsConnected
    {
        get { return isConnected; }
    }
}
