package com.group.idm2;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.EditText;

import androidx.appcompat.app.AppCompatActivity;

public class HomeActivity extends AppCompatActivity {
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_home);
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

    }
    public void signOut(View view) {
        Intent login = new Intent(this,LoginActivity.class);
        login.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
        login.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
        startActivity(login);
    }
}
