#include<bits/stdc++.h>
using namespace std;

typedef vector<int> vi;
typedef pair<int,int> pii;
typedef long long int lld;

#define sz                           size()
#define pb                           push_back
#define mp                           make_pair
#define F                            first
#define S                            second
#define fill(a,v)                    memset((a),(v),sizeof (a))
#define INF                          INT_MAX
#define mod 1000000007
#define __sync__             std::ios::sync_with_stdio(false);
#define all(a)                 a.begin(),a.end()

typedef long double ld;

//Total number of people voting
int S;
//Total number of people supposed to win
int N;

ld t; //threshold

vector<string>  winners;
vector< stack<string> > ballots;
//in the top down order of each persons preference

map<string, ld> votes; //map because I dont know input format
map<string, bool> notInContention; //whether he/she is still in the race

/*
 * Transfer votes down from 'name', who has received k >= t votes
 */
void transferDown(string name, ld k)
{
    ld f = (k - t)/k;
    for (auto &b : ballots)
    {
        while (!b.empty() && notInContention[b.top()]) b.pop();
        if (!b.empty() && b.top() == name)
        {
            b.pop();
            while (!b.empty() && notInContention[b.top()]) b.pop();
            if (!b.empty())
            {
                votes[b.top()] += f;
            }
        }
    }
}

/*
 * Transfer votes up from 'name', who got least votes in a stage and so, was eliminated.
 */
void transferUp(string name, ld k)
{
    for (auto &b : ballots)
    {
        while (!b.empty() && notInContention[b.top()]) b.pop();
        if (!b.empty() && b.top() == name)
        {
            b.pop();
            while (!b.empty() && notInContention[b.top()]) b.pop();
            if (!b.empty())
            {
                votes[b.top()] += 1;
            }
        }
    }
}

void getWinners()
{
    t = (int)(1.0 * S / (N + 1)) + 1;
    int left = N;
    printf("electorate attendance: %d, threshold: %Lg\n", S, t);
    while ((int)winners.sz < N)
    {
        printf("============\nVotes:\n");
        for (auto v: votes) {
            printf("%s: %Lg\n", v.F.c_str(), v.S);
        }
        printf("============\n\n");
        if ((int)votes.sz <= left)
        {
            for (auto elem : votes) winners.pb(elem.F);
            return;
        }

        auto it = *max_element(votes.begin(), votes.end(),
                [](const pair<string, ld>& p1, const pair<string, ld>& p2) {return p1.second < p2.second; });
        if (notInContention[it.F]) continue;
	    printf("Candidate %s has max votes (%Lg)\n", it.F.c_str(), it.S);
        if (it.S >= t)
        {
	        printf("Candidate wins! (%Lg > %Lg), transferring down\n", it.S, t);
            transferDown(it.F, it.S);
            winners.pb(it.F);
            notInContention[it.F] = 1;
            votes.erase(it.F);
            left--;
        }
        else
        {
            printf("Not enough votes to win (%Lg not greater than %Lg)\n", it.S, t);
            auto x = *min_element(votes.begin(), votes.end(),
                    [](const pair<string, ld>& p1, const pair<string, ld>& p2) {return p1.second < p2.second; });
	        printf("Candidate %s has min votes (%Lg), transferring votes up\n", x.F.c_str(), x.S);
            transferUp(x.F, x.S);
            notInContention[x.F] = 1;
            votes.erase(x.F);
        }
    }
}

void parse(string s)
{
    stack<string> tmp;
    vector<string> tmp1;
    int x = 0;
    int f = 1;
    while (x < s.length())
    {
        string name = "";
        while (x < s.length() && s[x] != ' ') name += s[x++];
        tmp1.pb(name);
        if (f) votes[name]++, f = 0;
        else votes[name];
        x++;
    }
    for (int i=(int)tmp1.sz-1;i>=0;i--) tmp.push(tmp1[i]);
    ballots.pb(tmp);
}

int main(int argc, char** argv)
{
    S = 0;
    if (argc < 3) {
        printf("usage: stv <votes file> <delegation size>\n");
        return 1;
    }
    ifstream in(argv[1]);
    N = atoi(argv[2]);
    printf("Delegation size: %d\n", N);
    while (!in.eof())
    {
        string line;
        getline(in, line);
        parse(line);
        S++;
    }
    S -= 1; // correction for off-by-one error due to eof()
    getWinners();
    printf("============\nWinners are:\n");
    for (string &w : winners) cout << w << "\n";
}
