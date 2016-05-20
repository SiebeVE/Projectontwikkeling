﻿using UnityEngine;
using UnityEngine.SceneManagement;
using System.Collections;

/// <summary>
/// Contains logic for determing the device's location.
/// </summary>
public class LocationManager {

    // variables for storing latitude and longitude
    private static float lat, lon;
    public static bool hasFailed = false;

    #region Properties
    public static float Latitude
    {
        get { return lat; }
    }

    public static float Longitude
    {
        get { return lon; }
    }
    #endregion

    #region Methods
    /// <summary>
    /// Determine device's current location when the service is enabled.
    /// </summary>
    public static void DetermineLocation()
    {
        // check if GPS is enabled
        if (IsLocationServiceEnabled())        // it's enabled
        {
            Input.location.Start(10f, 10f);

            if(Input.location.status == LocationServiceStatus.Failed)
            {
                hasFailed = true;
            }
            else
            {
                // Pass current location to location variables.
                lat = Input.location.lastData.latitude;
                lon = Input.location.lastData.longitude;

                MapManager.SetAddress(string.Empty);
            }
        }
        else  // Location service is not enabled
        {
            hasFailed = true;
        }

        // finally stop the location service
        Input.location.Stop();

        if (SceneManager.GetActiveScene().name == "Login")
        {
            UIHandler.LoadMainScene("Main");
        }
    }

    /// <summary>
    /// Check whether Unity's locationservice is enabled.
    /// </summary>
    /// <returns>True if the service is enabled. False otherwise.</returns>
    private static bool IsLocationServiceEnabled()
    {
        if (Input.location.isEnabledByUser)
        {
            return true;
        }

        return false;
    }
    #endregion
}
