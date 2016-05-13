using UnityEngine;
using UnityEngine.UI;
using UnityEngine.SceneManagement;
using System.Collections;

/// <summary>
/// Handles all UI related subjects. (Finding GO's, assign functions, responsive design...)
/// </summary>
public class UIHandler : MonoBehaviour {

    #region Unity
    private GameObject main, overlay_obj;           // MAIN = main content of the screen; OVERLAY_OBJ = object to be displayed in maps
    public static GameObject error_message, mainHome, mainProjectsListView, project_page;     // ERROR = the error to be displayed at startup, MAINHOME = the home screen; MAINPROJECTSLISTVIEW = projectlistview, PROJECT_PAGE = page of the project to be loaded

    #region UI
    // STARTUP = button at login screen ; CONFIRM (LOGIN) = when error is displayed at startup; PROJECT = project button in home screen; MAPS = maps button in home screen; SETTINGS = settings button in home screen; WEBSITE = website button in home screen; CONFIRM (MAPS) = ok button in maps screen;
    private Button startup_bttn, confirm_bttn_login, project_bttn, maps_bttn, settings_bttn, website_bttn, confirm_bttn_maps;
    private InputField location_input; // MAPS screen
    private Dropdown maptype_drop;  // MAPS screen
    private Slider zoomSlider, scaleSlider; // MAPS screen
    public static Text errorM;  // text to be displayed when an error occurs at startup
    #endregion
    #endregion

    /// <summary>
    /// name of the menu which should be loaded when the user presses the back button
    /// </summary>
    public static string mNameOfMenu = "";

    public Button[] projectTest_bttns = new Button[] { };

    void Awake()
    {
        if(SceneManager.GetActiveScene().name == "Login")
        {
            error_message = GameObject.Find("Canvas").transform.Find("error_message").gameObject;

            startup_bttn = GameObject.Find("startup_bttn").GetComponent<Button>();
            confirm_bttn_login = error_message.transform.Find("confirm_bttn").GetComponent<Button>();

            errorM = error_message.transform.Find("message").GetComponent<Text>();

            startup_bttn.onClick.AddListener(() => StartCoroutine(NetworkManager.CheckInternetConnection(NetworkManager.URL)));
            confirm_bttn_login.onClick.AddListener(() => Application.Quit());
        }
        else if (SceneManager.GetActiveScene().name == "Main")
        {
            main = GameObject.Find("Main");
            mainHome = main.transform.Find("Home").gameObject;
            mainProjectsListView = main.transform.Find("Project").gameObject;
            project_page = Resources.Load<GameObject>("Prefabs/project_page");

            // Find all buttons on the main screen
            project_bttn = mainHome.transform.Find("projecten_bttn").GetComponent<Button>();
            maps_bttn = mainHome.transform.Find("maps_bttn").GetComponent<Button>();
            settings_bttn = mainHome.transform.Find("settings_bttn").GetComponent<Button>();
            website_bttn = mainHome.transform.Find("website_bttn").GetComponent<Button>();

            // Assign tasks to these buttons
            project_bttn.onClick.AddListener(() => ActivateMenu(mainProjectsListView, mainHome));
            maps_bttn.onClick.AddListener(() => SceneManager.LoadScene("Maps"));
            website_bttn.onClick.AddListener(() => Application.OpenURL(NetworkManager.URL));

            for(byte i = 0; i < projectTest_bttns.Length; i++)
            {
                projectTest_bttns[i].onClick.AddListener(() => ActivateMenu(LoadProjectPage(), mainProjectsListView));
            }
        }
        else if(SceneManager.GetActiveScene().name == "Maps")
        {
            overlay_obj = GameObject.Find("Canvas").transform.Find("overlay_obj").gameObject;

            confirm_bttn_maps = overlay_obj.transform.Find("confirm_bttn").GetComponent<Button>();
            location_input = overlay_obj.transform.Find("location_input").GetComponent<InputField>();
            maptype_drop = overlay_obj.transform.Find("maptype_drop").GetComponent<Dropdown>();
            zoomSlider = overlay_obj.transform.Find("zoom_sldr").GetComponent<Slider>();
            scaleSlider = overlay_obj.transform.Find("scale_sldr").GetComponent<Slider>();
        }
    }

    /// <summary>
    /// This method will be called when there has been an error during startup.
    /// </summary>
    public static void CheckForError()
    {
        if (errorM.text != string.Empty)         // there has been an error
        {
            // display the error
            error_message.SetActive(true);
        }
    }

    /// <summary>
    /// Loads the Main scene when all requirements are met.
    /// </summary>
    /// <param name="sceneName">The name of the scene which will be loaded.</param>
    public static void LoadMainScene(string sceneName)
    {
        SceneManager.LoadScene(sceneName);
    }

    /// <summary>
    /// Activates the desired menu on the screen, depending on where you are in the app.
    /// </summary>
    /// <param name="menuToActivate">The menu which should be called.</param>
    /// <param name="menuToDisable">The menu which should be disabled when the other is called.</param>
    public static void ActivateMenu(GameObject menuToActivate, GameObject menuToDisable)
    {
        menuToDisable.SetActive(false);
        menuToActivate.SetActive(true);

        ReturnToMenu(menuToDisable.name);
    }

    /// <summary>
    /// Returns the gameobject name which we came from (for finding out which menu should be called)
    /// </summary>
    /// <param name="nameOfMenu">The name of the menu which should be activated.</param>
    public static string ReturnToMenu(string nameOfMenu)
    {
        return mNameOfMenu = nameOfMenu;
    }

    /// <summary>
    /// When a button is pressed in the project_listview, the project page gets instantiated.
    /// </summary>
    /// <returns>The project page gameobject.</returns>
    private GameObject LoadProjectPage()
    {
        var instance = Instantiate(project_page);
        instance.name = "project_page";
        instance.transform.SetParent(main.transform, false);

        return instance;
    }



}
