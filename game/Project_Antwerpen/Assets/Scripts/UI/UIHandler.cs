using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.SceneManagement;
using UnityEngine.UI;

/// <summary>
/// Handles all UI related subjects. (Finding GO's, assign functions, responsive design...)
/// </summary>
public class UIHandler : MonoBehaviour {

    #region Unity
    private GameObject main, overlay_obj, menu, menuBG, project_listbttn;
    public static GameObject mainHome, mainProject, mainSettings, project_page, warning, map_list_item, grid;
    #region UI
    public static Button startup_bttn, project_bttn, maps_bttn, settings_bttn, website_bttn, confirm_bttn_maps, burger_bttn, menu_home, menu_projects, menu_maps, menu_settings, menu_website, menu_logout, warning_bttn;

    /// <summary>
    /// Text component of the location field in the map screen
    /// </summary>
    public static Text location_string;

    public static InputField location_input;
    public static Dropdown maptype_drop;
    public static Slider zoomSlider,radiusSlider, timerSlider;
    public static RawImage map;
    #endregion
    #endregion

    #region fields
    /// <summary>
    /// Name of the menu which should be loaded when the user presses the back button
    /// </summary>
    private const byte MIN_TIMER_WARNING = 5;
    private const float STANDARD_TIMER_VALUE = 15f, STANDARD_RADIUS_VALUE = 10f;
    public static int timer_slider_value;
    #endregion

    void Awake()
    {
        // Set the scene name
        Commons.SCENE_NAME = SceneManager.GetActiveScene().name;

        if (Commons.SCENE_NAME == Commons.LOGIN_SCENE_NAME)
        {
            startup_bttn = GameObject.Find("startup_bttn").GetComponent<Button>();

            startup_bttn.onClick.AddListener(() =>
                {
                    //StartCoroutine(NetworkManager.CheckInternetConnection(NetworkManager.ping));
                    StartCoroutine(ProjectManager.GetProjects());
                });
        }
        else if (Commons.SCENE_NAME == Commons.MAIN_SCENE_NAME)
        {
            Commons.NAME_OF_MENU = "";

            #region Finding Gameobjects
            // warning gameobject
            warning = GameObject.Find("warning");
            warning_bttn = warning.transform.Find("close_warning").GetComponent<Button>();

            // Find the main functioning objects
            main = GameObject.Find("Main");
            mainHome = main.transform.Find("Home").gameObject;
            mainProject = main.transform.Find("Project").gameObject;
            mainSettings = main.transform.Find("Settings").gameObject;

            // Main menu code
            menu = GameObject.Find("Menu");
            menuBG = menu.transform.Find("BG").gameObject;

            menu_projects = menuBG.transform.Find("menu_projects").GetComponent<Button>();
            menu_maps = menuBG.transform.Find("menu_maps").GetComponent<Button>();
            menu_settings = menuBG.transform.Find("menu_settings").GetComponent<Button>();

            // settings code
            radiusSlider = mainSettings.transform.Find("radius_sldr/Slider").GetComponent<Slider>();
            timerSlider = mainSettings.transform.Find("timer_sldr/Slider").GetComponent<Slider>();

            // Find the resources
            project_page = Resources.Load<GameObject>("Prefabs/project_page");
            project_listbttn = Resources.Load<GameObject>("Prefabs/project_listbttn");

            // Find all buttons on the main screen
            project_bttn = mainHome.transform.Find("projecten_bttn").GetComponent<Button>();
            maps_bttn = mainHome.transform.Find("maps_bttn").GetComponent<Button>();
            settings_bttn = mainHome.transform.Find("settings_bttn").GetComponent<Button>();
            website_bttn = mainHome.transform.Find("website_bttn").GetComponent<Button>();
            burger_bttn = GameObject.Find("Header").transform.Find("hamburger").gameObject.GetComponent<Button>();
            #endregion

            warning_bttn.onClick.AddListener(() => DisableWarning());

            // Assign tasks to these buttons
            project_bttn.onClick.AddListener(() => ActivateMenu(mainProject, mainHome));

            maps_bttn.onClick.AddListener(() =>
            {
                // to be sure, make sure the user will always see the roadmap type first
                MapManager.Maptype = MapType.roadmap;
                MapManager.SetAddress(string.Empty, ProjectManager.projects);
                Commons.LoadScene(Commons.MAPS_SCENE_NAME);
            });

            settings_bttn.onClick.AddListener(() => ActivateMenu(mainSettings, mainHome));

            // Clicking on the maps button in the main menu brings us to the map screen
            menu_maps.onClick.AddListener(() =>
                {
                    // to be certain, makesure the user will always see the roadmap type first
                    MapManager.Maptype = MapType.roadmap;
                    MapManager.SetAddress(string.Empty, ProjectManager.projects);
                    Commons.LoadScene(Commons.MAPS_SCENE_NAME);
                });

            // open website
            website_bttn.onClick.AddListener(() => Application.OpenURL(NetworkManager.URL));

            menu_settings.onClick.AddListener(() =>
                    {
                        GameObject obj = null;

                        if(Commons.NAME_OF_MENU == "")
                        {
                            obj = mainHome;
                        }
                        else if(Commons.NAME_OF_MENU == Commons.HOME_MENU)
                        {
                            obj = mainProject;
                        }
                        else
                        {
                            obj = GameObject.Find("project_page");
                        }

                        ActivateMenu(mainSettings, obj);

                        ShowMainMenu();
                    });

            menu_projects.onClick.AddListener(() => 
                    {
                        if (Commons.NAME_OF_MENU == "") // the previous menu is null, so we can assume we are in the home screen
                        {
                            ActivateMenu(mainProject, mainHome);
                        }
                        else if(mainSettings.activeInHierarchy) // we are in the settings screen
                        {
                            ActivateMenu(mainProject, mainSettings);
                        }
                        else if(Commons.NAME_OF_MENU == Commons.PROJECTS_MENU)   // we are on a project_page
                        {
                            ActivateMenu(mainProject, GameObject.Find("project_page"));
                        }
                        ShowMainMenu();
                    });

            // set a radius to determine which projects should be loaded
            radiusSlider.onValueChanged.AddListener((value) => radiusSlider.transform.Find("value").GetComponent<Text>().text = value.ToString());

            timerSlider.onValueChanged.AddListener((value) =>
                {
                    timerSlider.transform.Find("value").GetComponent<Text>().text = value.ToString();
                    timer_slider_value = (int)value;

                    if (value < MIN_TIMER_WARNING)  // Show a warning when the user sets a timer value less than 5 minutes
                    {
                        mainSettings.transform.Find("timer_sldr/warning").gameObject.SetActive(true);
                    }
                    else
                    {
                        mainSettings.transform.Find("timer_sldr/warning").gameObject.SetActive(false);
                    }
                });

            // First load the settings from the previous session
            // if they exist
            if (PlayerPrefs.HasKey("radius"))
            {
                SettingsManager.LoadData();
            }
            else // set standards
            {
                radiusSlider.value = STANDARD_RADIUS_VALUE;
                timerSlider.value = STANDARD_TIMER_VALUE;

                SettingsManager.SaveData();
            }

            timer_slider_value = (int)timerSlider.value;

            // we want the location to be updated after a certain amount of time
            InvokeRepeating("CallDetermineLocation", 0, (timer_slider_value * 60));   // We multiply by 60 to measure the time in minutes (Standard is seconds)
        }
        else if(Commons.SCENE_NAME == Commons.MAPS_SCENE_NAME)
        {
            GameObject listview = GameObject.Find("Main").transform.Find("list_go").gameObject;     // the listview in the mapsscreen
            location_string = listview.transform.Find("list_part/list/grid/Location/Lbl").GetComponent<Text>(); // text component in the buttons in the list view
            overlay_obj = GameObject.Find("menu_maps"); // menu in mapsscreen

            // bars button in header
            burger_bttn = listview.transform.Find("Header/hamburger").GetComponent<Button>();

            // Main menu code
            menu = listview.transform.Find("list_part/Menu").gameObject;
            menuBG = menu.transform.Find("BG").gameObject;

            // find the map in the scene
            map = GameObject.Find("Main").transform.Find("map").GetComponent<RawImage>();

            // find all components in the overlay menu
            confirm_bttn_maps = overlay_obj.transform.Find("confirm_bttn").GetComponent<Button>();
            location_input = overlay_obj.transform.Find("location_input").GetComponent<InputField>();
            maptype_drop = overlay_obj.transform.Find("maptype_drop").GetComponent<Dropdown>();
            zoomSlider = overlay_obj.transform.Find("zoom_sldr").GetComponent<Slider>();

            // find the list item which needs to be instantiated into the list
            map_list_item = Resources.Load<GameObject>("Prefabs/map_list_item");
            grid = GameObject.Find("Main").transform.Find("list_go/list_part/list/grid").gameObject;

            confirm_bttn_maps.onClick.AddListener(() =>
                     {
                         StartCoroutine(CheckLocationName());
                         GetComponent<AnimatorHandler>().DisableAnimator(overlay_obj.GetComponent<Animator>());
                     });

            zoomSlider.onValueChanged.AddListener((value) => 
                    {
                        ZoomMap(value);
                        StartCoroutine(CheckLocationName());
                    });

            maptype_drop.onValueChanged.AddListener((value) =>
                {
                    ChangeMapType(value);
                    StartCoroutine(CheckLocationName());
                });
        };

        if (Commons.SCENE_NAME == Commons.MAIN_SCENE_NAME || Commons.SCENE_NAME == Commons.MAPS_SCENE_NAME)
        {
            // Find all main menu buttons, this is the same for the main screen as for the maps screen
            menu_home = menuBG.transform.Find("menu_home").GetComponent<Button>();
            menu_website = menuBG.transform.Find("menu_website").GetComponent<Button>();
            menu_logout = menuBG.transform.Find("menu_logout").GetComponent<Button>();

            // assign tasks to these buttons
            menu_home.onClick.AddListener(() => 
                {
                    Commons.LoadScene(Commons.MAIN_SCENE_NAME);
                    SettingsManager.SaveData();
                });

            menu_website.onClick.AddListener(() => Application.OpenURL(NetworkManager.URL));
            menu_logout.onClick.AddListener(() => 
                {
                    Commons.LoadScene(Commons.LOGIN_SCENE_NAME);
                    SettingsManager.SaveData();
                });

            burger_bttn.onClick.AddListener(() => ShowMainMenu());
        }
    }

    /// <summary>
    /// Checks if a location has been filled in and if it's different from the already returned value.
    /// </summary>
    private IEnumerator CheckLocationName()
    {
        // ask for the latitude and logitude of the desired location
        yield return StartCoroutine(MapManager.ReturnLatLong(location_input.text, ProjectManager.projects)); // wait until finished
        
        // update the location name in the listview live with the map
        yield return StartCoroutine(MapManager.ReturnLocationName(location_input.text, location_string));
    }

    /// <summary>
    /// Shows a warning when location couldn't be updated, otherwise the maps URL is set.
    /// </summary>
    public static void ShowWarning()
    {
        if (!NetworkManager.IsConnected || LocationManager.hasFailed)
        {
            Camera.main.GetComponent<AnimatorHandler>().EnableAnimator(warning.GetComponent<Animator>());
        }
        else
        {
            MapManager.SetAddress(string.Empty, ProjectManager.projects);
        }
    }

    /// <summary>
    /// Method to be called after the amount of time we specified.
    /// </summary>
    private void CallDetermineLocation()
    {
        StartCoroutine(NetworkManager.CheckInternetConnection(NetworkManager.ping));
    }

    /// <summary>
    /// Changes the maptype live.
    /// </summary>
    /// <param name="maptype">the selected value in the dropdownlist</param>
    private void ChangeMapType(int maptype)
    {
        MapManager.Maptype = (MapType)maptype;
    }

    /// <summary>
    /// Changes the zoom level of the map (live).
    /// </summary>
    /// <param name="zoomlevel">The value of the slider object.</param>
    private void ZoomMap(float zoomlevel)
    {
        MapManager.Zoom = (int)zoomlevel;
    }

    /// <summary>
    /// When the close_Warning button is clicked, we want the warning to disappear 
    /// </summary>
    public void DisableWarning()
    {
        GetComponent<AnimatorHandler>().DisableAnimator(warning.GetComponent<Animator>());
    }

    /// <summary>
    /// Activates the desired menu on the screen, depending on where you are in the app.
    /// </summary>
    /// <param name="menuToActivate">The menu which should be called.</param>
    /// <param name="menuToDisable">The menu which should be disabled when the other is called.</param>
    public static void ActivateMenu(GameObject menuToActivate, GameObject menuToDisable)
    {
        if (menuToDisable.name == mainHome.name || menuToDisable.name == mainProject.name || menuToDisable.name == mainSettings.name)
        {
            if(menuToDisable.name == mainSettings.name)
            {
                SettingsManager.SaveData();
            }

            menuToDisable.SetActive(false);
            Commons.ReturnToMenu(menuToDisable.name);
        }
        else if(menuToDisable.name == "project_page")
        {
            Commons.ReturnToMenu(mainHome.name);
            DestroyImmediate(GameObject.Find("project_page"), true);
        }

        menuToActivate.SetActive(true);
    }

    /// <summary>
    /// When a button is pressed in the project_listview, the project page gets instantiated.
    /// </summary>
    /// <param name="name">The name of the project so all the correct properties will be showed on the screen.</param>
    /// <returns>The project page gameobject.</returns>
    private GameObject LoadProjectPage(string name)
    {
        GameObject prPage;
        RawImage prImage;
        Text prTitle, prPlace, prStage, prDescription;
        Project p;
         
        // instantiate the project page and give it the name project_page
        var instance = Instantiate(project_page);
        instance.name = "project_page";

        // set the parent to be the main and give it the correct scale
        instance.transform.SetParent(main.transform, false);

        // find all the gameobjects on the page
        prPage = GameObject.Find(instance.name).transform.Find("grid").gameObject;
        prImage = prPage.transform.Find("project_image").GetComponent<RawImage>();
        prTitle = prPage.transform.Find("project_image/project_title_BG/project_title").GetComponent<Text>();
        prPlace = prPage.transform.Find("project_image/project_title_BG/project_place").GetComponent<Text>();
        prStage = prPage.transform.Find("project_fase/fase_txt").GetComponent<Text>();
        prDescription = prPage.transform.Find("project_description/description").GetComponent<Text>();

        p = FindProject(ProjectManager.projects, name);

        prTitle.text = p.Name;

        // Return the location of the given project
        StartCoroutine(MapManager.ReturnLocationName(p, prPlace));

        // set the image background
        StartCoroutine(ProjectManager.ReturnImage(p.ImagePath, prImage));

        prStage.text = p.CurrentStage;
        prDescription.text = p.Description;

        return instance;
    }

    /// <summary>
    /// Finds the corresponding project in the list of loaded projects, so the correct information is displayed on the project page
    /// </summary>
    /// <param name="projects">The project list of the projectHandler, which was loaded from the database.</param>
    /// <param name="value">The name of the project of which the info should be loaded.</param>
    /// <returns>The project object corresponding to the name of the button which was clicked.</returns>
    private Project FindProject(List<Project> projects, string value)
    {
        return projects.Find(p => p.Name == value);
    }

    /// <summary>
    /// Shows the main menu gameobject on screen
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

            instance.GetComponent<Button>().onClick.AddListener(() => ActivateMenu(LoadProjectPage(instance.name), mainProject));
        }
    }
}
