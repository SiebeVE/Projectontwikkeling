using UnityEngine;
using System.Collections.Generic;

public class testProjectHandler : MonoBehaviour {

    public List<Project> projecten = new List<Project>();
    public Sprite[] images = new Sprite[] { };

    void Awake()
    {
        projecten.Add(new Project("Project1", "beschrijving project 1", 51.172506f, 4.369673f, images[0], new List<Stage>()
                                                                      {
                                                                        new Stage("Hallo Project 1", new System.DateTime(2016, 5, 18), new System.DateTime(2016, 6, 1)),
                                                                        new Stage("Fase 2", new System.DateTime(2016, 6, 1), new System.DateTime(2016,6,5)),
                                                                        new Stage("Fase 3", new System.DateTime(2016, 6,5), new System.DateTime(2016, 6, 13)),
                                                                        new Stage("Fase 4", new System.DateTime(2016, 6, 13), new System.DateTime(2016, 6,18))
                                                                      }));

        projecten.Add(new Project("Project2", "beschrijving project 2", 51.173650f, 4.366947f, images[1],new List<Stage>()
                                                                      {
                                                                        new Stage("Dag Project 2", new System.DateTime(2016, 5, 18), new System.DateTime(2016, 6, 1)),
                                                                        new Stage("Fase 2", new System.DateTime(2016, 6, 1), new System.DateTime(2016,6,5)),
                                                                        new Stage("Fase 3", new System.DateTime(2016, 6,5), new System.DateTime(2016, 6, 13)),
                                                                        new Stage("Fase 4", new System.DateTime(2016, 6, 13), new System.DateTime(2016, 6,18))
                                                                      }));

        projecten.Add(new Project("Project3", "beschrijving project 3", 51.173912f, 4.372666f, images[2], new List<Stage>()
                                                                      {
                                                                        new Stage("Ik ben project 3", new System.DateTime(2016, 5, 18), new System.DateTime(2016, 6, 1)),
                                                                        new Stage("Fase 2", new System.DateTime(2016, 6, 1), new System.DateTime(2016,6,5)),
                                                                        new Stage("Fase 3", new System.DateTime(2016, 6,5), new System.DateTime(2016, 6, 13)),
                                                                        new Stage("Fase 4", new System.DateTime(2016, 6, 13), new System.DateTime(2016, 6,18))
                                                                      }));

        projecten.Add(new Project("Project4", "beschrijving project 4", 51.172944f, 4.371969f, images[3], new List<Stage>()
                                                                      {
                                                                        new Stage("Mimimimi, dit is 4", new System.DateTime(2016, 5, 18), new System.DateTime(2016, 6, 1)),
                                                                        new Stage("Fase 2", new System.DateTime(2016, 6, 1), new System.DateTime(2016,6,5)),
                                                                        new Stage("Fase 3", new System.DateTime(2016, 6,5), new System.DateTime(2016, 6, 13)),
                                                                        new Stage("Fase 4", new System.DateTime(2016, 6, 13), new System.DateTime(2016, 6,18))
                                                                      }));

        projecten.Add(new Project("Project5", "beschrijving project 5", 51.172283f, 4.373460f, images[4], new List<Stage>()
                                                                      {
                                                                        new Stage("En als laatste hebben we 5", new System.DateTime(2016, 5, 5), new System.DateTime(2016, 5, 17)),
                                                                        new Stage("Fase 2", new System.DateTime(2016, 5, 17), new System.DateTime(2016,6,5)),
                                                                        new Stage("Fase 3", new System.DateTime(2016, 6,5), new System.DateTime(2016, 6, 13)),
                                                                        new Stage("Fase 4", new System.DateTime(2016, 6, 13), new System.DateTime(2016, 6,18))
                                                                      }));
    }

    // Use this for initialization
    void Start() {

        for (byte i = 0; i < projecten.Count; i++)
        {
            projecten[i].DetermineCurrentStage(projecten[i].Stages);
        }

        // Load the buttons inside the list (on display)
        GetComponent<UIHandler>().LoadProjectList(projecten);
	
	}
	
	// Update is called once per frame
	void Update () {
	
	}
}
