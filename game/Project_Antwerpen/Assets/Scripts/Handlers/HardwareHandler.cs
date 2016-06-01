using UnityEngine;

/// <summary>
/// Detects whether a hardware button is pressed.
/// </summary>
public class HardwareHandler : MonoBehaviour {

    /// <summary>
    /// The main menu is the menu which is accessible all time (when pressing the A-button in the header)
    /// </summary>
    public bool mainMenuIsEnabled = false;

	// Use this for initialization
	void Start () {

        if(Commons.SCENE_NAME == Commons.MAIN_SCENE_NAME || Commons.SCENE_NAME == Commons.LOGIN_SCENE_NAME)
        {
            Screen.orientation = ScreenOrientation.Portrait;
        }
        else if (Commons.SCENE_NAME == Commons.MAPS_SCENE_NAME)
        {
            Screen.orientation = ScreenOrientation.Landscape;
        }
	}
	
	// Update is called once per frame
	void Update () {
        if (!mainMenuIsEnabled) // if the main menu is not enabled
        {
            if (Commons.SCENE_NAME == Commons.MAPS_SCENE_NAME)
            {
                if (Input.GetKey(KeyCode.Escape))
                {
                    Commons.LoadScene(Commons.MAIN_SCENE_NAME);
                }
            }
            else if (Commons.SCENE_NAME == Commons.MAIN_SCENE_NAME)
            {
                if (Commons.NAME_OF_MENU == Commons.HOME_MENU)                                 // the user came from Home, so he is in the project listview
                {
                    if (Input.GetKey(KeyCode.Escape))                               // if he presses back
                    {
                        UIHandler.ActivateMenu(UIHandler.mainHome, UIHandler.mainProject); // active the home screen
                        Commons.NAME_OF_MENU = "";                                 // then we want the name of menu to be empty, so if the user then clicks on the button, the app closes
                    }
                }
                else if (Commons.NAME_OF_MENU == "")                            // if the name is empty, we are in the home scree
                {
                    if (Input.GetKey(KeyCode.Escape) && !mainMenuIsEnabled)                           // if he then presses back
                    {
                        Application.Quit();                                     // Quit the app
                    }
                }
                else if (Commons.NAME_OF_MENU == Commons.PROJECTS_MENU)
                {
                    if (Input.GetKey(KeyCode.Escape))
                    {
                        DestroyImmediate(GameObject.Find("project_page"), true);
                        UIHandler.mainProject.SetActive(true);
                        Commons.NAME_OF_MENU = Commons.HOME_MENU;
                    }
                }
            }
        }
        else      // the main menu is enabled
        {
            // no matter where we are, we want the menu to dissapear
            if (Input.GetKey(KeyCode.Escape))
            {
                Camera.main.GetComponent<AnimatorHandler>().DisableAnimator(GetComponent<Animator>());
                mainMenuIsEnabled = false;
            }
        }

        if (Input.GetKey(KeyCode.Menu) && !mainMenuIsEnabled)
        {
            Camera.main.GetComponent<AnimatorHandler>().EnableAnimator(GetComponent<Animator>());
            mainMenuIsEnabled = true;
        }
        else if (Input.GetKey(KeyCode.Menu) && mainMenuIsEnabled)
        {
            Camera.main.GetComponent<AnimatorHandler>().DisableAnimator(GetComponent<Animator>());
            mainMenuIsEnabled = false;
        }
    }
}
