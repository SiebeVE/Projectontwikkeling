using UnityEngine;
using UnityEngine.SceneManagement;
using UnityEngine.UI;
using System.Collections;

/// <summary>
/// Detects whether a hardware button is pressed.
/// </summary>
public class HardwareHandler : MonoBehaviour {

    public Text t;

	// Use this for initialization
	void Start () {

        if(SceneManager.GetActiveScene().name == "Main")
        {
            Screen.orientation = ScreenOrientation.Portrait;
        }
        else if (SceneManager.GetActiveScene().name == "Maps")
        {
            Screen.orientation = ScreenOrientation.Landscape;
        }
	}
	
	// Update is called once per frame
	void Update () {
        t.text = UIHandler.mNameOfMenu;

        if (SceneManager.GetActiveScene().name == "Maps")
        {
            if (Input.GetKey(KeyCode.Escape))
            {
                SceneManager.LoadScene("Main");
            }
        }
        else if(SceneManager.GetActiveScene().name == "Main")
        {
            if (UIHandler.mNameOfMenu == "Home")                                 // the user came from Home, so he is in the project listview
            {
                if (Input.GetKey(KeyCode.Escape))                               // if he presses back
                {
                    UIHandler.ActivateMenu(UIHandler.mainHome, UIHandler.mainProjectsListView); // active the home screen
                    UIHandler.mNameOfMenu = "";                                 // then we want the name of menu to be empty, so if the user then clicks on the button, the app closes
                }
            }
            else if (UIHandler.mNameOfMenu == "")                            // if the name is empty, we are in the home scree
            {
                if (Input.GetKey(KeyCode.Escape))                           // if he then presses back
                {
                    Application.Quit();                                     // Quit the app
                }
            }
            else if (UIHandler.mNameOfMenu == "Project")
            {
                if (Input.GetKey(KeyCode.Escape))
                {
                    DestroyImmediate(GameObject.Find("project_page"), true);
                    UIHandler.mainProjectsListView.SetActive(true);
                    UIHandler.mNameOfMenu = "Home";
                }
            }
        }
	}
}
