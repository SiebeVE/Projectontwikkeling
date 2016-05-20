﻿using UnityEngine;
using System.Collections;
using System.Collections.Generic;

public class testProjectHandler : MonoBehaviour {

    public List<Project> projecten = new List<Project>();
    public Sprite[] images = new Sprite[] { };

    void Awake()
    {
        projecten.Add(new Project("Project1", "beschrijving project 1", images[0], new List<Stage>()
                                                                      {
                                                                        new Stage("Hallo Project 1", new System.DateTime(2016, 5, 18), new System.DateTime(2016, 6, 1)),
                                                                        new Stage("Fase 2", new System.DateTime(2016, 6, 1), new System.DateTime(2016,6,5)),
                                                                        new Stage("Fase 3", new System.DateTime(2016, 6,5), new System.DateTime(2016, 6, 13)),
                                                                        new Stage("Fase 4", new System.DateTime(2016, 6, 13), new System.DateTime(2016, 6,18))
                                                                      }));

        projecten.Add(new Project("Project2", "beschrijving project 2", images[1],new List<Stage>()
                                                                      {
                                                                        new Stage("Dag Project 2", new System.DateTime(2016, 5, 18), new System.DateTime(2016, 6, 1)),
                                                                        new Stage("Fase 2", new System.DateTime(2016, 6, 1), new System.DateTime(2016,6,5)),
                                                                        new Stage("Fase 3", new System.DateTime(2016, 6,5), new System.DateTime(2016, 6, 13)),
                                                                        new Stage("Fase 4", new System.DateTime(2016, 6, 13), new System.DateTime(2016, 6,18))
                                                                      }));

        projecten.Add(new Project("Project3", "beschrijving project 3", images[2], new List<Stage>()
                                                                      {
                                                                        new Stage("Ik ben project 3", new System.DateTime(2016, 5, 18), new System.DateTime(2016, 6, 1)),
                                                                        new Stage("Fase 2", new System.DateTime(2016, 6, 1), new System.DateTime(2016,6,5)),
                                                                        new Stage("Fase 3", new System.DateTime(2016, 6,5), new System.DateTime(2016, 6, 13)),
                                                                        new Stage("Fase 4", new System.DateTime(2016, 6, 13), new System.DateTime(2016, 6,18))
                                                                      }));

        projecten.Add(new Project("Project4", "beschrijving project 4", images[3], new List<Stage>()
                                                                      {
                                                                        new Stage("Mimimimi, dit is 4", new System.DateTime(2016, 5, 18), new System.DateTime(2016, 6, 1)),
                                                                        new Stage("Fase 2", new System.DateTime(2016, 6, 1), new System.DateTime(2016,6,5)),
                                                                        new Stage("Fase 3", new System.DateTime(2016, 6,5), new System.DateTime(2016, 6, 13)),
                                                                        new Stage("Fase 4", new System.DateTime(2016, 6, 13), new System.DateTime(2016, 6,18))
                                                                      }));

        projecten.Add(new Project("Project5", "beschrijving project 5", images[4], new List<Stage>()
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
            Debug.Log(projecten[i].CurrentStage);
        }

        // Load the buttons inside the list (on display)
        GetComponent<UIHandler>().LoadProjectList(projecten);
	
	}
	
	// Update is called once per frame
	void Update () {
	
	}
}
