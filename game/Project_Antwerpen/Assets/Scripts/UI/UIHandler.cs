using UnityEngine;
using UnityEngine.UI;
using UnityEngine.SceneManagement;
using System.Collections;

/// <summary>
/// Handles all UI related subjects. (Finding GO's, assign functions, responsive design...)
/// </summary>
public class UIHandler : MonoBehaviour {

    private Button projects, maps, settings, website;
    public string URL = "https://www.google.be";

    void Awake()
    {
        projects = GameObject.Find("projecten_bttn").GetComponent<Button>();
        maps = GameObject.Find("maps_bttn").GetComponent<Button>();
        settings = GameObject.Find("settings_bttn").GetComponent<Button>();
        website = GameObject.Find("website_bttn").GetComponent<Button>();

        maps.onClick.AddListener(() => SceneManager.LoadScene("test"));
        website.onClick.AddListener(() => Application.OpenURL(URL));
    }

	// Use this for initialization
	void Start () {
	
	}
	
	// Update is called once per frame
	void Update () {
	
	}
}
