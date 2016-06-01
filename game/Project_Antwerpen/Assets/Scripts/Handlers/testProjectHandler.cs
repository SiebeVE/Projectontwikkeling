using UnityEngine;

public class testProjectHandler : MonoBehaviour {

    // Use this for initialization
    void Start() {

        // determine the current stage of each project
        for (byte i = 0; i < ProjectManager.projects.Count; i++)
        {
            ProjectManager.projects[i].DetermineCurrentStage(ProjectManager.projects[i].Stages);
        }

        // Load the buttons inside the list (on display)
        GetComponent<UIHandler>().LoadProjectList(ProjectManager.projects);
	
	}
}
