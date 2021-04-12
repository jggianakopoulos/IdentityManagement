package com.group.idm2.Activities;

import android.content.Intent;
import android.os.Build;
import android.os.Bundle;
import android.view.View;

import com.group.idm2.R;

import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;

public class HomeActivity extends DrawerActivity {
    protected void onCreate(Bundle savedInstanceState) {
        setContentView(R.layout.activity_home);
        this.header = "Home";
        super.onCreate(savedInstanceState);
    }

    public void updateProfile(View view) {
        Intent send = new Intent(this, ProfileActivity.class);
        this.startActivity(send);
    }

    public void changePassword(View view) {
        Intent send = new Intent(this, PasswordActivity.class);
        this.startActivity(send);
    }

    public void updateFace(View view) {
        Intent send = new Intent(this, FaceActivity.class);
        this.startActivity(send);
    }
}
