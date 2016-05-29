using LitJson;
using System;
using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

/// <summary>
/// Manager responsible for loading projects into the game
/// </summary>
public class ProjectManager {

    public static List<Project> projects = new List<Project>();
    //public Sprite[] images = new Sprite[] { };

    void Awake()
    {
        //projects.Add(new Project("Project1", "beschrijving project 1", 51.172506f, 4.369673f, images[0], new List<Stage>()
        //                                                              {
        //                                                                new Stage("Hallo Project 1", new System.DateTime(2016, 5, 18), new System.DateTime(2016, 6, 1)),
        //                                                                new Stage("Fase 2", new System.DateTime(2016, 6, 1), new System.DateTime(2016,6,5)),
        //                                                                new Stage("Fase 3", new System.DateTime(2016, 6,5), new System.DateTime(2016, 6, 13)),
        //                                                                new Stage("Fase 4", new System.DateTime(2016, 6, 13), new System.DateTime(2016, 6,18))
        //                                                              }));

        //projects.Add(new Project("Project2", "beschrijving project 2", 51.173650f, 4.366947f, images[1], new List<Stage>()
        //                                                              {
        //                                                                new Stage("Dag Project 2", new System.DateTime(2016, 5, 18), new System.DateTime(2016, 6, 1)),
        //                                                                new Stage("Fase 2", new System.DateTime(2016, 6, 1), new System.DateTime(2016,6,5)),
        //                                                                new Stage("Fase 3", new System.DateTime(2016, 6,5), new System.DateTime(2016, 6, 13)),
        //                                                                new Stage("Fase 4", new System.DateTime(2016, 6, 13), new System.DateTime(2016, 6,18))
        //                                                              }));

        //projects.Add(new Project("Project3", "beschrijving project 3", 51.173912f, 4.372666f, images[2], new List<Stage>()
        //                                                              {
        //                                                                new Stage("Ik ben project 3", new System.DateTime(2016, 5, 18), new System.DateTime(2016, 6, 1)),
        //                                                                new Stage("Fase 2", new System.DateTime(2016, 6, 1), new System.DateTime(2016,6,5)),
        //                                                                new Stage("Fase 3", new System.DateTime(2016, 6,5), new System.DateTime(2016, 6, 13)),
        //                                                                new Stage("Fase 4", new System.DateTime(2016, 6, 13), new System.DateTime(2016, 6,18))
        //                                                              }));

        //projects.Add(new Project("Project4", "beschrijving project 4", 51.172944f, 4.371969f, images[3], new List<Stage>()
        //                                                              {
        //                                                                new Stage("Mimimimi, dit is 4", new System.DateTime(2016, 5, 18), new System.DateTime(2016, 6, 1)),
        //                                                                new Stage("Fase 2", new System.DateTime(2016, 6, 1), new System.DateTime(2016,6,5)),
        //                                                                new Stage("Fase 3", new System.DateTime(2016, 6,5), new System.DateTime(2016, 6, 13)),
        //                                                                new Stage("Fase 4", new System.DateTime(2016, 6, 13), new System.DateTime(2016, 6,18))
        //                                                              }));

        //projects.Add(new Project("Project5", "beschrijving project 5", 51.172283f, 4.373460f, images[4], new List<Stage>()
        //                                                              {
        //                                                                new Stage("En als laatste hebben we 5", new System.DateTime(2016, 5, 5), new System.DateTime(2016, 5, 17)),
        //                                                                new Stage("Fase 2", new System.DateTime(2016, 5, 17), new System.DateTime(2016,6,5)),
        //                                                                new Stage("Fase 3", new System.DateTime(2016, 6,5), new System.DateTime(2016, 6, 13)),
        //                                                                new Stage("Fase 4", new System.DateTime(2016, 6, 13), new System.DateTime(2016, 6,18))
        //                                                              }));

        //projects.Add(new Project("Project6", "beschrijving project 6", 51.1638351f, 4.139883f, images[4], new List<Stage>()
        //                                                              {
        //                                                                new Stage("En als laatste hebben we 5", new System.DateTime(2016, 5, 5), new System.DateTime(2016, 5, 17)),
        //                                                                new Stage("Here comes the sixth!", new System.DateTime(2016, 5, 17), new System.DateTime(2016,6,5)),
        //                                                                new Stage("Fase 3", new System.DateTime(2016, 6,5), new System.DateTime(2016, 6, 13)),
        //                                                                new Stage("Fase 4", new System.DateTime(2016, 6, 13), new System.DateTime(2016, 6,18))
        //                                                              }));
    }

    /// <summary>
    /// Retrieves the list of projects from the database.
    /// </summary>
    /// <returns>A list of projects.</returns>
    public static IEnumerator GetProjects()
    {
        WWW www = new WWW("https://laravel.siebeve.be/api/get/projects?secret=teamTof");
        yield return www;
        
        if(www.error == null) // there was no error
        {
            JsonData json = JsonMapper.ToObject(www.text);

            if(json[Commons.STATUS].ToString().ToUpper() == Commons.OK_STATUS_CODE) // we get an "ok" status code, so there was a connection
            {
                // return the projects from the database
                ReturnProjects(json, "projects"); 
            }
        }

        // in both cases (internet or not) we want to reach the app
        Commons.LoadScene(Commons.MAIN_SCENE_NAME);
    }

    /// <summary>
    /// Returns a new project with the correct information and adds it to the list of projects.
    /// </summary>
    /// <param name="data">Json object of which the data should be read.</param>
    /// <param name="type">Which type of object are we looking for?</param>
    private static void ReturnProjects(JsonData data, string type)
    {
        string name, description, lat, lon, photo_path;

        for (int i = 0; i < data[type].Count; i++)
        {
            name = CheckValues(data[type][i], "name");
            description = CheckValues(data[type][i], "description");
            lat = CheckValues(data[type][i], "latitude");
            lon = CheckValues(data[type][i], "longitude");
            photo_path = CheckValues(data[type][i], "photo_path");

            projects.Add(new Project(name, description, float.Parse(lat), float.Parse(lon), photo_path, ReturnListOfStages(data[type][i]["phases"])));
        }
    }

    /// <summary>
    /// Returns a list of stages for the current project.
    /// </summary>
    /// <param name="data">Where in the JSON is the data stored?</param>
    /// <returns>A list of stages for the current project</returns>
    private static List<Stage> ReturnListOfStages(JsonData data)
    {
        List<Stage> tempStages = new List<Stage>();

        // are there stages for the current project?
        if(data.Count != 0)
        {
            for(int i = 0; i < data.Count; i++)
            {
                // add them to our list
                tempStages.Add(
                        new Stage(data[i]["name"].ToString(), 
                        DateTime.Parse(data[i]["start"].ToString()), 
                        DateTime.Parse(data[i]["end"].ToString())
                    )); 
            }
        }

        // return the list
        return tempStages;
    }

    /// <summary>
    /// Check if the value for the searched parameter is equals to null.
    /// </summary>
    /// <param name="data">The JSON object of which the data is read.</param>
    /// <param name="value">The parameter we want the value from.</param>
    private static string CheckValues(JsonData data, string value)
    {
        if(value == "latitude" || value == "longitude")
        {
            return data[value] != null ? data[value].ToString() : "0";
        }
        else
        {
            return data[value] != null ? data[value].ToString() : "null";
        }
    }

    public static IEnumerator ReturnImage(string imageURL, Image image)
    {
        WWW www = new WWW(NetworkManager.URL + imageURL);
        yield return www;

        if(www.error == null)
        {
            //image.sprite = www.texture;
        }
    }

}
