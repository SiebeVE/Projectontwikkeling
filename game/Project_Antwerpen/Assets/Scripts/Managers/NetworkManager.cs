using UnityEngine;
using System.Collections;
using System.Net;

/// <summary>
/// Contains all logic which has to do with internet access.
/// </summary>
public class NetworkManager {

    private static bool isConnected = false;

    public static IEnumerator CheckInternetConnection(string IPaddress)
    {
        if (Application.internetReachability == NetworkReachability.ReachableViaCarrierDataNetwork || Application.internetReachability == NetworkReachability.ReachableViaLocalAreaNetwork)
        {
            Ping ping = new Ping(IPaddress);
            float startTime = Time.time;

            while (Time.time < startTime + 5.0f)
            {
                yield return new WaitForSeconds(0.1f);
            }

            if (ping.isDone)
            {
                isConnected = true;
            }
            else
            {
                isConnected = false;
            }
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
