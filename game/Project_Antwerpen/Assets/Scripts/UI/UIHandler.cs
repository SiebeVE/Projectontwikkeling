using UnityEngine;
using UnityEngine.UI;
using UnityEngine.SceneManagement;
using System.Collections.Generic;

/// <summary>
/// Handles all UI related subjects. (Finding GO's, assign functions, responsive design...)
/// </summary>
public class UIHandler : MonoBehaviour {

    #region Unity
    private GameObject main, overlay_obj, menu, menuBG, project_listbttn;           // MAIN = main content of the screen; OVERLAY_OBJ = object to be displayed in maps
    public static GameObject error_message, mainHome, mainProject, project_page;     // ERROR = the error to be displayed at startup, MAINHOME = the home screen; MAINPROJECT = projectlistview, PROJECT_PAGE = page of the project to be loaded
    private testProjectHandler tprh;

    #region UI
    // STARTUP = button at login screen ; CONFIRM (LOGIN) = when error is displayed at startup; PROJECT = project button in home screen; MAPS = maps button in home screen; SETTINGS = settings button in home screen; WEBSITE = website button in home screen; CONFIRM (MAPS) = ok button in maps screen; LOGO = logo in home screen; all buttons starting with menu_ are the buttons in the menu in the main screen
    private Button startup_bttn, confirm_bttn_login, project_bttn, maps_bttn, settings_bttn, website_bttn, confirm_bttn_maps, logo_bttn, menu_home, menu_projects, menu_maps, menu_settings, menu_website, menu_logout;
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

    //public Button[] projectTest_bttns = new Button[] { };

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
            tprh = GetComponent<testProjectHandler>();

            // Find the main functioning objects
            main = GameObject.Find("Main");
            mainHome = main.transform.Find("Home").gameObject;
            mainProject = main.transform.Find("Project").gameObject;

            // Find the resources
            project_page = Resources.Load<GameObject>("Prefabs/project_page");
            project_listbttn = Resources.Load<GameObject>("Prefabs/project_listbttn");

            // Find all buttons on the main screen
            project_bttn = mainHome.transform.Find("projecten_bttn").GetComponent<Button>();
            maps_bttn = mainHome.transform.Find("maps_bttn").GetComponent<Button>();
            settings_bttn = mainHome.transform.Find("settings_bttn").GetComponent<Button>();
            website_bttn = mainHome.transform.Find("website_bttn").GetComponent<Button>();
            logo_bttn = GameObject.Find("Header").transform.Find("logo").gameObject.GetComponent<Button>();

            // Assign tasks to these buttons
            project_bttn.onClick.AddListener(() => ActivateMenu(mainProject, mainHome));
            maps_bttn.onClick.AddListener(() => SceneManager.LoadScene("Maps"));
            website_bttn.onClick.AddListener(() => Application.OpenURL(NetworkManager.URL));
            logo_bttn.onClick.AddListener(() => ShowMainMenu());

            // menu code
            menu = GameObject.Find("Menu");
            menuBG = menu.transform.Find("BG").gameObject;
            menu_home = menuBG.transform.Find("menu_home").GetComponent<Button>();
            menu_projects = menuBG.transform.Find("menu_projects").GetComponent<Button>();
            menu_maps = menuBG.transform.Find("menu_maps").GetComponent<Button>();
            menu_settings = menuBG.transform.Find("menu_settings").GetComponent<Button>();
            menu_website = menuBG.transform.Find("menu_website").GetComponent<Button>();
            menu_logout = menuBG.transform.Find("menu_logout").GetComponent<Button>();

            // assign tasks to these buttons
            menu_home.onClick.AddListener(() => LoadMainScene(SceneManager.GetActiveScene().name));

            menu_projects.onClick.AddListener(() => 
                    {   ActivateMenu(mainProject, mainHome);
                        ShowMainMenu();
                    });

            menu_maps.onClick.AddListener(() => LoadMainScene("Maps"));
            menu_website.onClick.AddListener(() => Application.OpenURL(NetworkManager.URL));
            menu_logout.onClick.AddListener(() => LoadMainScene("Login"));

            //for (byte i = 0; i < projectTest_bttns.Length; i++)
            //{
            //    projectTest_bttns[i].onClick.AddListener(() => ActivateMenu(LoadProjectPage(), mainProjectsListView));
            //}
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

    /// <summary>
    /// Enables/Disables the menu in the main screen when the logo is clicked.
    /// </summary>
    private void ShowMainMenu()
    {
        if (menu.GetComponent<HardwareHandler>().mainMenuIsEnabled)
        {
            GetComponent<AnimatorHandler>().DisableAnimator(menu.GetComponent<Animator>());
            menu.GetComponent<HardwareHandler>().mainMenuIsEnabled = false;
        }
        else
        {
            GetComponent<AnimatorHandler>().EnableAnimator(menu.GetComponent<Animator>());
            menu.GetComponent<HardwareHandler>().mainMenuIsEnabled = true;
        }
    }

    /// <summary>
    /// When the projects are loaded from the database, a list of buttons should be instantiated in the listview.
    /// </summary>
    /// <param name="projects">The list of project which needs to be displayed on the screen.</param>
    public void LoadProjectList(List<Project> projects)
    {
        GameObject grid = mainProject.transform.Find("project_listview/grid").gameObject;

        for(byte i = 0; i < projects.Count; i++)
        {
            // Instantiate for each project in the list a button
            var instance = Instantiate(project_listbttn);

            // Give this button (GameObject) the name of the project so it can be easily found in the hierarchy
            instance.name = projects[i].Name;
            // Also set the text of the button, to be the correct name of the project
            instance.GetComponentInChildren<Text>().text = projects[i].Name;

            // Set the parent of the instance to be the grid, so it buttons will be displayed in there
            // and give it the correct size
            instance.transform.SetParent(grid.transform, false);
        }
    }

}
