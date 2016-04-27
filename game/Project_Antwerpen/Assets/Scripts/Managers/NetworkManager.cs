using UnityEngine;
using System.Collections;

/// <summary>
/// Contains all logic which has to do with internet access.
/// </summary>
public class NetworkManager {

    private static bool isConnected = false;

    /// <summary>
    /// Check for internet access capability.
    /// </summary>
    /// <param name="ipAdress">IP-address to ping to.</param>
    /// <returns></returns>
	public static IEnumerator CheckInternetConnection(string ipAddress)
    { 
        // Variables for checking whether the ping test should continue or not
        float passedTime = 0.0f;
        float maxTime = 2.0f;

        while (true)
        {
            Ping ping = new Ping(ipAddress);

            while (!ping.isDone)
            {
                passedTime += Time.deltaTime;

                if(passedTime > maxTime)    // time has exceeded maxtime
                {
                    // there is no internet connection
                    isConnected = false;
                    break;
                }

                yield return null;
            }

            // testing ping is done
            if(passedTime < maxTime)    // the time which has ellapsed is below the maxtime
            {
                // user is connected
                isConnected = true;
                yield return null;
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
