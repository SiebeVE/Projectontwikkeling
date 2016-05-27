using UnityEngine;

/// <summary>
/// Saving and loading of settings data between scenes and game sessions.
/// </summary>
public static class SettingsManager {
    
    /// <summary>
    /// Saves the data in the preferences.
    /// </summary>
    public static void SaveData()
    {
        PlayerPrefs.SetInt("radius", (int)UIHandler.radiusSlider.value);
        PlayerPrefs.SetInt("timer", (int)UIHandler.timerSlider.value);
        PlayerPrefs.Save();
    }

    /// <summary>
    /// Loads the data from the preferences if the corresponding keys exist.
    /// </summary>
    public static void LoadData()
    {
        UIHandler.radiusSlider.value = PlayerPrefs.GetInt("radius");
        UIHandler.timerSlider.value = PlayerPrefs.GetInt("timer");
    }
    
}
