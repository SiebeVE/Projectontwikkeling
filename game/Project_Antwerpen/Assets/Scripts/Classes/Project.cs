using System;
using System.Collections.Generic;
using UnityEngine;

public class Project {

    private string mName, mDescription;
    private Sprite mImage;
    private List<Stage> mStages;
    private string mCurrentStage;

    public Project(string name, string description, Sprite image, List<Stage> stages)
    {
        mName = name;
        mDescription = description;
        mImage = image;

        mStages = stages;
    }

    #region Properties
    public string Name
    {
        get { return mName; }
    }

    public string Description
    {
        get { return mDescription; }
    }

    public List<Stage> Stages
    {
        get { return mStages; }
    }

    public string CurrentStage
    {
        get { return mCurrentStage; }
    }

    public Sprite Image
    {
        get { return mImage; }
    }
    #endregion

    /// <summary>
    /// Determines the current stage of a project.
    /// </summary>
    /// <param name="stages">The list of stages of this project.</param>
    public void DetermineCurrentStage(List<Stage> stages)
    {
        for(byte i = 0; i < stages.Count; i++)
        {
            if(DateTime.Compare(DateTime.Today, stages[i].BeginDate) >= 0 && DateTime.Compare(DateTime.Today, stages[i].EndDate) <= 0)
            {
                // todays date is later than the begindate AND earlier than the enddate of the current stage
                mCurrentStage = stages[i].Name;
                break;
            }
        }
    }
}
