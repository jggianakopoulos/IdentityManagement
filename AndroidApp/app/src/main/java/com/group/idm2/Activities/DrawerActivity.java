package com.group.idm2.Activities;

import android.app.Activity;
import android.content.Intent;
import android.os.Build;
import android.os.Bundle;
import android.view.View;

import com.group.idm2.R;
import com.mikepenz.materialdrawer.Drawer;
import com.mikepenz.materialdrawer.DrawerBuilder;
import com.mikepenz.materialdrawer.model.DividerDrawerItem;
import com.mikepenz.materialdrawer.model.PrimaryDrawerItem;
import com.mikepenz.materialdrawer.model.SecondaryDrawerItem;
import com.mikepenz.materialdrawer.model.interfaces.IDrawerItem;

import androidx.appcompat.widget.Toolbar;

public class DrawerActivity extends AbstractActivity {
    public String header;
    public Toolbar toolBar;

    protected void onCreate(Bundle savedInstanceState) {
        toolBar = (Toolbar) findViewById(R.id.toolbar);

        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
            toolBar.setTitle(header);
            setSupportActionBar(toolBar);
            getDrawer(this,toolBar);
        }
        super.onCreate(savedInstanceState);
    }

    public void getDrawer(final Activity activity, Toolbar toolbar) {
        PrimaryDrawerItem drawerEmptyItem= new PrimaryDrawerItem().withIdentifier(0).withName("");
        drawerEmptyItem.withEnabled(false);

        PrimaryDrawerItem changeSignIn = new PrimaryDrawerItem().withIdentifier(1)
                .withName("Change Sign-in Options");
        PrimaryDrawerItem faceUpdate = new PrimaryDrawerItem().withIdentifier(2)
                .withName("Update Face");
        PrimaryDrawerItem profileSettings = new PrimaryDrawerItem().withIdentifier(3)
                .withName("Profile Settings");
        PrimaryDrawerItem changePassword = new PrimaryDrawerItem().withIdentifier(4)
                .withName("Change Password");
        SecondaryDrawerItem logout = new SecondaryDrawerItem().withIdentifier(5)
                .withName("Logout");

        Drawer result = new DrawerBuilder()
                .withActivity(activity)
                .withToolbar(toolbar)
                .withActionBarDrawerToggle(true)
                .withActionBarDrawerToggleAnimated(true)
                .withCloseOnClick(true)
                .withSelectedItem(-1)
                .addDrawerItems(
                        drawerEmptyItem,
                        changeSignIn,
                        faceUpdate,
                        profileSettings,
                        changePassword,
                        new DividerDrawerItem(),
                        logout
                )
                .withOnDrawerItemClickListener(new Drawer.OnDrawerItemClickListener() {
                    @Override
                    public boolean onItemClick(View view, int position, IDrawerItem drawerItem) {
                        if (drawerItem.getIdentifier() == 1) {
                            Intent intent = new Intent(activity, SignInMethodsActivity.class);
                            view.getContext().startActivity(intent);
                        } else if (drawerItem.getIdentifier() == 2) {
                            Intent intent = new Intent(activity, FaceActivity.class);
                            view.getContext().startActivity(intent);
                        } else if (drawerItem.getIdentifier() == 3) {
                            Intent intent = new Intent(activity, ProfileActivity.class);
                            view.getContext().startActivity(intent);
                        } else if (drawerItem.getIdentifier() == 4) {
                            Intent intent = new Intent(activity, PasswordActivity.class);
                            view.getContext().startActivity(intent);
                        } else if (drawerItem.getIdentifier() == 5) {
                            signOut();
                        }
                        return true;
                    }
                })
                .build();
    }

}