using UnityEngine;

public class testProjectHandler : MonoBehaviour {

    ProjectManager prM;

    void Awake()
    {
        prM = GetComponent<ProjectManager>();
    }

    // Use this for initialization
    void Start() {

        for (byte i = 0; i < prM.projects.Count; i++)
        {
            prM.projects[i].DetermineCurrentStage(prM.projects[i].Stages);
        }

        // Load the buttons inside the list (on display)
        GetComponent<UIHandler>().LoadProjectList(prM.projects);
	
	}
}
