using UnityEngine.SceneManagement;

public static class Commons {

    public static string OK_STATUS_CODE = "OK";
    public static string STATUS = "status";

    public static char[] ALPHABET = new char[25] { 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z' };

    public static string SCENE_NAME = "";
    public static string LOGIN_SCENE_NAME = "Login";
    public static string MAIN_SCENE_NAME = "Main";
    public static string MAPS_SCENE_NAME = "Maps";

    public static string HOME_MENU = "Home";
    public static string PROJECTS_MENU = "Project";
    //public static string SETTINGS_MENU = "Settings";

    public static string NAME_OF_MENU = "";

    public static string API_KEY = "&key=AIzaSyAn26km9c6rfD7sWftyRa29QjwISSsIF9I";

    /// <summary>
    /// Loads the required scene.
    /// </summary>
    /// <param name="sceneName">The scene name to be loaded.</param>
    public static void LoadScene(string sceneName)
    {
        SceneManager.LoadScene(sceneName);
    }

    /// <summary>
    /// Returns the gameobject name which we came from (for finding out which menu should be called)
    /// </summary>
    /// <param name="nameOfMenu">The name of the menu which should be activated.</param>
    public static string ReturnToMenu(string nameOfMenu)
    {
        return NAME_OF_MENU = nameOfMenu;
    }
}
