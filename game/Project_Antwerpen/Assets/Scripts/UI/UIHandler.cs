﻿using UnityEngine;
using UnityEngine.UI;
using UnityEngine.SceneManagement;
using System.Collections.Generic;

/// <summary>
/// Handles all UI related subjects. (Finding GO's, assign functions, responsive design...)
/// </summary>
public class UIHandler : MonoBehaviour {

    #region Unity
    private GameObject main, overlay_obj, menu, menuBG, project_listbttn;
    public static GameObject mainHome, mainProject, project_page, warning;
    #region UI
    public Button startup_bttn, project_bttn, maps_bttn, settings_bttn, website_bttn, confirm_bttn_maps, burger_bttn, menu_home, menu_projects, menu_maps, menu_settings, menu_website, menu_logout, warning_bttn;
    private InputField location_input;
    private Dropdown maptype_drop;
    private Slider zoomSlider;
    public static RawImage map;
    #endregion
    #endregion

    /// <summary>
    /// name of the menu which should be loaded when the user presses the back button
    /// </summary>
    public static string mNameOfMenu = "";

    void Awake()
    {
        string sceneName = SceneManager.GetActiveScene().name;

        if (sceneName == "Login")
        {
            startup_bttn = GameObject.Find("startup_bttn").GetComponent<Button>();

            startup_bttn.onClick.AddListener(() => StartCoroutine(NetworkManager.CheckInternetConnection(NetworkManager.ping)));
        }
        else if (sceneName == "Main")
        {
            mNameOfMenu = "";

            // warning gameobject
            warning = GameObject.Find("warning");
            warning_bttn = warning.transform.Find("close_warning").GetComponent<Button>();

            warning_bttn.onClick.AddListener(() => DisableWarning());

            // Find the main functioning objects
            main = GameObject.Find("Main");
            mainHome = main.transform.Find("Home").gameObject;
            mainProject = main.transform.Find("Project").gameObject;

            // Main menu code
            menu = GameObject.Find("Menu");
            menuBG = menu.transform.Find("BG").gameObject;

            menu_projects = menuBG.transform.Find("menu_projects").GetComponent<Button>();
            menu_maps = menuBG.transform.Find("menu_maps").GetComponent<Button>();
            menu_settings = menuBG.transform.Find("menu_settings").GetComponent<Button>();

            // Find the resources
            project_page = Resources.Load<GameObject>("Prefabs/project_page");
            project_listbttn = Resources.Load<GameObject>("Prefabs/project_listbttn");

            // Find all buttons on the main screen
            project_bttn = mainHome.transform.Find("projecten_bttn").GetComponent<Button>();
            maps_bttn = mainHome.transform.Find("maps_bttn").GetComponent<Button>();
            settings_bttn = mainHome.transform.Find("settings_bttn").GetComponent<Button>();
            website_bttn = mainHome.transform.Find("website_bttn").GetComponent<Button>();
            burger_bttn = GameObject.Find("Header").transform.Find("hamburger").gameObject.GetComponent<Button>();

            // Assign tasks to these buttons
            project_bttn.onClick.AddListener(() => ActivateMenu(mainProject, mainHome));
            maps_bttn.onClick.AddListener(() => SceneManager.LoadScene("Maps"));
  
            website_bttn.onClick.AddListener(() => Application.OpenURL(NetworkManager.URL));

            menu_projects.onClick.AddListener(() => 
                    {
                        if (mNameOfMenu == "")
                        {
                            ActivateMenu(mainProject, mainHome);
                        }
                        else
                        {
                            ActivateMenu(mainProject, GameObject.Find("project_page"));
                        }
                        ShowMainMenu();
                    });            
        }
        else if(sceneName == "Maps")
        {
            GameObject listview = GameObject.Find("Main").transform.Find("list_go").gameObject;
            overlay_obj = GameObject.Find("menu_maps");

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

            confirm_bttn_maps.onClick.AddListener(() =>
                     {
                         MapManager.SetAddress(location_input.text);
                         StartCoroutine(MapManager.LoadMap(MapManager.URLaddress));
                         GetComponent<AnimatorHandler>().DisableAnimator(overlay_obj.GetComponent<Animator>());
                     });

            zoomSlider.onValueChanged.AddListener((value) => ZoomMap(value));
            maptype_drop.onValueChanged.AddListener((value) => ChangeMapType(value));
        }

        if (sceneName == "Main" || sceneName == "Maps")
        {
            // Find all main menu buttons, this is the same for the main screen as for the maps screen
            menu_home = menuBG.transform.Find("menu_home").GetComponent<Button>();
            menu_website = menuBG.transform.Find("menu_website").GetComponent<Button>();
            menu_logout = menuBG.transform.Find("menu_logout").GetComponent<Button>();

            // assign tasks to these buttons
            menu_home.onClick.AddListener(() => LoadMainScene("Main"));
            menu_website.onClick.AddListener(() => Application.OpenURL(NetworkManager.URL));
            menu_logout.onClick.AddListener(() => LoadMainScene("Login"));
            burger_bttn.onClick.AddListener(() => ShowMainMenu());
        }
    }

    /// <summary>
    /// If an error occured we want the warning to be shown.
    /// </summary>
    public static void ShowWarning()
    {
        if(!NetworkManager.IsConnected || LocationManager.hasFailed)
        {
            Camera.main.GetComponent<AnimatorHandler>().EnableAnimator(warning.GetComponent<Animator>());
        }
    }

    /// <summary>
    /// Changes the maptype live.
    /// </summary>
    /// <param name="maptype">the selected value in the dropdownlist</param>
    private void ChangeMapType(int maptype)
    {
        MapManager.Maptype = (MapType)maptype;
        MapManager.SetAddress(location_input.text);
        StartCoroutine(MapManager.LoadMap(MapManager.URLaddress));
    }

    /// <summary>
    /// Changes the zoom level of the map (live).
    /// </summary>
    /// <param name="zoomlevel">The value of the slider object.</param>
    private void ZoomMap(float zoomlevel)
    {
        MapManager.Zoom = (int)zoomlevel;
        MapManager.SetAddress(location_input.text);
        StartCoroutine(MapManager.LoadMap(MapManager.URLaddress));
    }

    /// <summary>
    /// When the close_Warning button is clicked, we want the warning to disappear 
    /// </summary>
    public void DisableWarning()
    {
        GetComponent<AnimatorHandler>().DisableAnimator(warning.GetComponent<Animator>());
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
        if (menuToDisable.name == mainHome.name || menuToDisable.name == mainProject.name)
        {
            menuToDisable.SetActive(false);
            ReturnToMenu(menuToDisable.name);
        }
        else if(menuToDisable.name == "project_page")
        {
            ReturnToMenu(mainHome.name);
            DestroyImmediate(GameObject.Find("project_page"), true);
        }

        menuToActivate.SetActive(true);
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
    /// <param name="name">The name of the project so all the correct properties will be showed on the screen.</param>
    /// <returns>The project page gameobject.</returns>
    private GameObject LoadProjectPage(string name)
    {
        GameObject prPage;
        Image prImage;
        Text prTitle, prStage, prDescription;
        Project p;
         
        // instantiate the project page and give it the name project_page
        var instance = Instantiate(project_page);
        instance.name = "project_page";

        // set the parent to be the main and give it the correct scale
        instance.transform.SetParent(main.transform, false);

        // find all the gameobjects on the page
        prPage = GameObject.Find(instance.name).transform.Find("grid").gameObject;
        prImage = prPage.transform.Find("project_image").GetComponent<Image>();
        prTitle = prPage.transform.Find("project_image/project_title_BG/project_title").GetComponent<Text>();
        prStage = prPage.transform.Find("project_fase").GetComponentInChildren<Text>();
        prDescription = prPage.transform.Find("project_description/description").GetComponent<Text>();

        p = FindProject(GetComponent<testProjectHandler>().projecten, name);

        prTitle.text = p.Name;
        prImage.sprite = p.Image;
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
